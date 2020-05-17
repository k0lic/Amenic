<?php namespace App\Controllers;

/*
    Author: Andrija Kolić
    Github: k0lic
*/

use App\Models\CinemaModel;
use App\Models\UserModel;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\ComingSoonModel;
use App\Models\MovieModel;
use App\Models\GalleryModel;
use App\Models\AACinemaModel;
use App\Entities\Gallery;
use Exception;
use function \App\Helpers\isAuthenticated;
use function \App\Helpers\isValid;

class Theatre extends BaseController
{
    private string $userMail = "";
    private $userImage = null;
    private string $userName = "";

    // Does nothing, for now.
    public function index()
    {
        return view("404.php");
    }

    // Shows the repertoire of the chosen cinema page. 
    public function repertoire($email)
    {
        $this->tasteTheCookie();
        $cinema = (new CinemaModel())->find($email);
        if ($cinema == null)
            return view("404.php");
        $cinemaImage = ((new UserModel())->find($email))->image;
        $cinemaCity = $cinema->idCity == null ? null : (new CityModel())->find($cinema->idCity);
        $cinemaCountry = $cinema->idCountry == null ? ($cinemaCity == null ? null : (new CountryModel())->find($cinemaCity->idCountry)) : (new CountryModel())->find($cinema->idCountry);
        $gallery = (new GalleryModel())->where("email", $email)->find();
        if (empty($this->userMail))                 // for guests
            return view("Cinema/CinemaPublic.php", ["cinema" => $cinema,"cinemaImage" => $cinemaImage,"cinemaCity" => $cinemaCity->name,"cinemaCountry" => $cinemaCountry->name,"gallery" => $gallery,"userIsLoggedIn" => false]);
        else                                        // for registered users (RUsers)
            return view("Cinema/CinemaPublic.php", ["cinema" => $cinema,"cinemaImage" => $cinemaImage,"cinemaCity" => $cinemaCity->name,"cinemaCountry" => $cinemaCountry->name,"gallery" => $gallery,"userIsLoggedIn" => true,"userImage" => $this->userImage,"userFullName" => $this->userName]);
    }

    // Adds a new image to the gallery of the chosen cinema.
    public function actionAddImage()
    {
        $email = $_POST["email"];
        $imageName = $_FILES["newImage"]["name"];
        $imageTempFile = $_FILES["newImage"]["tmp_name"];

        try
        {
            $image = base64_encode(file_get_contents($imageTempFile));
            $galleryRow = new Gallery([
                "email" => $email,
                "name" => $imageName,
                "image" => $image
            ]);
            (new GalleryModel())->insert($galleryRow);
        }
        catch (Exception $e)
        {
            $msg = "Adding a new image to the gallery failed!<br/>".$e->getMessage();
            return view("Exception.php",["msg" => $msg,"destination" => "/Theatre/Repertoire/$email"]);
        }


        header("Location: /Theatre/Repertoire/$email");
        exit();
    }

    // Fetch methods //

    // Fetches a page (20) of projections for a specific cinema, for a specific day.
    public function getMyRepertoire()
    {
        $cinemaEmail = $_REQUEST["cinemaEmail"];
        $day = $_REQUEST["day"];
        $page = $_REQUEST["page"];

        $aamdl = new AACinemaModel();
        $results = $aamdl->findMyMovieRepertoire($cinemaEmail, $day, $page);

        echo json_encode($results);
    }

    // Fetches how many projections there are for a specific cinema, for a specific day.
    public function countMyRepertoire()
    {
        $cinemaEmail = $_REQUEST["cinemaEmail"];
        $day = $_REQUEST["day"];

        $aamdl = new AACinemaModel();
        $results = $aamdl->countMyMovieRepertoire($cinemaEmail, $day);

        echo json_encode($results);
    }

    // Fetches a page (20) of movies that are coming soon for a specific cinema.
    public function getMyComingSoons()
    {
        $cinemaEmail = $_REQUEST["cinemaEmail"];
        $page = $_REQUEST["page"];

        $soonmdl = new ComingSoonModel();
        $moviemdl = new MovieModel();
        $comingSoons = $soonmdl->where("email", $cinemaEmail)->limit(20, ($page-1)*20)->find();
        $results = [];
        foreach ($comingSoons as $soon)
        {
            $movie = $moviemdl->find($soon->tmdbID);
            array_push($results, [
                "movieName" => $movie->title
            ]);
        }

        echo json_encode($results);
    }

    // Fetches how many movies that are coming soon are ther for a specific cinema.
    public function countMyComingSoons()
    {
        $cinemaEmail = $_REQUEST["cinemaEmail"];

        $soonmdl = new ComingSoonModel();
        $results = $soonmdl->where("email", $cinemaEmail)->countAllResults();

        echo json_encode($results);
    }

    // Private methods //

    private function tasteTheCookie()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null)
            {
                $this->userMail = $token->email;
                if (isAuthenticated("Cinema"))
                    $this->userName = $token->name;
                else
                    $this->userName = $token->firstName." ".$token->lastName;
                $this->userImage = ((new UserModel())->find($token->email))->image;
            }
        }
    }
}