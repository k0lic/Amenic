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
        $this->goHomeIfNotCinema();

        $cinema = (new CinemaModel())->find($this->userMail);
        $cinemaCity = $cinema->idCity == null ? null : (new CityModel())->find($cinema->idCity);
        $cinemaCountry = $cinema->idCountry == null ? ($cinemaCity == null ? null : (new CountryModel())->find($cinemaCity->idCountry)) : (new CountryModel())->find($cinema->idCountry);
        $gallery = (new GalleryModel())->where("email", $this->userMail)->find();

        return view("Cinema/CinemaPublic.php", ["cinema" => $cinema,"cinemaImage" => $this->userImage,"cinemaCity" => $cinemaCity->name,"cinemaCountry" => $cinemaCountry->name,"gallery" => $gallery,"userIsLoggedIn" => false,"cinemaIsLoggedIn" => true,"userImage" => $this->userImage,"userFullName" => $this->userName]);
        //return view("404.php");
    }

    // Shows the repertoire of the chosen cinema page. 
    public function repertoire($email)
    {
        $this->onlyBasicAccounts();

        $cinema = (new CinemaModel())->find($email);
        if ($cinema == null)
            return view("404.php");
        $cinemaImage = ((new UserModel())->find($email))->image;
        $cinemaCity = $cinema->idCity == null ? null : (new CityModel())->find($cinema->idCity);
        $cinemaCountry = $cinema->idCountry == null ? ($cinemaCity == null ? null : (new CountryModel())->find($cinemaCity->idCountry)) : (new CountryModel())->find($cinema->idCountry);
        $gallery = (new GalleryModel())->where("email", $email)->find();
        if (empty($this->userMail))                 // for guests
            return view("Cinema/CinemaPublic.php", ["cinema" => $cinema,"cinemaImage" => $cinemaImage,"cinemaCity" => $cinemaCity->name,"cinemaCountry" => $cinemaCountry->name,"gallery" => $gallery,"userIsLoggedIn" => false,"cinemaIsLoggedIn" => false]);
        else                                        // for registered users (RUsers)
            return view("Cinema/CinemaPublic.php", ["cinema" => $cinema,"cinemaImage" => $cinemaImage,"cinemaCity" => $cinemaCity->name,"cinemaCountry" => $cinemaCountry->name,"gallery" => $gallery,"userIsLoggedIn" => true,"cinemaIsLoggedIn" => false,"userImage" => $this->userImage,"userFullName" => $this->userName]);
    }

    // Adds a new image to the gallery of the chosen cinema.
    public function actionAddImage()
    {
        $this->goHomeIfNotPost();
        $this->goHomeIfNotCinema();

        $email = $this->userMail;
        $imageName = isset($_FILES["newImage"]["name"]) ? $_FILES["newImage"]["name"] : null;
        $imageTempFile = $this->request->getFile("newImage");

        $validationResult = $this->isValid("actionAddGalleryImage", [
            "imageName" => $imageName,
            "imageFile" => $imageTempFile
        ]);
        if ($validationResult != 1)
        {
            setcookie("addGalleryImageErrors", http_build_query($validationResult), time() + 3600, "/");
            header("Location: /Theatre");
            exit();
        }

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
            return view("Exception.php",["msg" => $msg,"destination" => "/Theatre"]);
        }


        header("Location: /Theatre");
        exit();
    }

    // Deletes an image from the gallery.
    public function actionDeleteImage()
    {
        $this->goHomeIfNotPost();
        $this->goHomeIfNotCinema();

        $validationResult = $this->isValid("actionDeleteGalleryImage", $_POST);
        if ($validationResult != 1)
        {
            header("Location: /Theatre");
            exit();
        }

        $imageName = $_POST["deleteImageName"];
        
        try
        {
            (new GalleryModel())->where("email", $this->userMail)->where("name", $imageName)->delete();
        }
        catch (Exception $e)
        {
            $msg = "Deleting an image from the gallery failed!<br/>".$e->getMessage();
            return view("Exception.php",["msg" => $msg,"destination" => "/Theatre"]);
        }

        header("Location: /Theatre");
        exit();
    }

    // Changes the cinema banner image.
    public function actionChangeBanner()
    {
        $this->goHomeIfNotPost();
        $this->goHomeIfNotCinema();

        $imageTempFile = $this->request->getFile("newBanner");

        $validationResult = $this->isValid("actionChangeBanner", [
            "bannerImage" => $imageTempFile
        ]);
        if ($validationResult != 1)
        {
            setcookie("addGalleryImageErrors", http_build_query($validationResult), time() + 3600, "/");
            header("Location: /Theatre");
            exit();
        }

        try
        {
            $image = base64_encode(file_get_contents($imageTempFile));
            (new CinemaModel())->where("email", $this->userMail)->set([
                "banner" => $image
            ])->update();
        }
        catch (Exception $e)
        {
            $msg = "Changing the banner image failed!<br/>".$e->getMessage();
            return view("Exception.php",["msg" => $msg,"destination" => "/Theatre"]);
        }

        header("Location: /Theatre");
        exit();
    }

    // Fetch methods //

    // Fetches a page (20) of projections for a specific cinema, for a specific day.
    public function getMyRepertoire()
    {
        $this->tasteTheCookie();

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
        $this->tasteTheCookie();

        $cinemaEmail = $_REQUEST["cinemaEmail"];
        $day = $_REQUEST["day"];

        $aamdl = new AACinemaModel();
        $results = $aamdl->countMyMovieRepertoire($cinemaEmail, $day);

        echo json_encode($results);
    }

    // Fetches a page (20) of movies that are coming soon for a specific cinema.
    public function getMyComingSoons()
    {
        $this->tasteTheCookie();
        
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
        $this->tasteTheCookie();
        
        $cinemaEmail = $_REQUEST["cinemaEmail"];

        $soonmdl = new ComingSoonModel();
        $results = $soonmdl->where("email", $cinemaEmail)->countAllResults();

        echo json_encode($results);
    }

    // Private methods //

    // Calls the validation service test named $testName to check validity of $data.
    private function isValid($testName, $data)
    {
        $validation =  \Config\Services::validation();

        $ret = $validation->run($data, $testName);

        if ($ret == 1)
            return 1;
        return $validation->getErrors();
    }

    // Only lets through: guests, registered users, or cinemas.
    private function tasteTheCookie()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null)
            {
                if (isAuthenticated("Cinema"))
                    $this->userName = $token->name;
                else if (isAuthenticated("RUser"))
                    $this->userName = $token->firstName." ".$token->lastName;
                else
                {
                    header("Location: /HomeController");
                    exit();
                }
                $this->userMail = $token->email;
                $this->userImage = ((new UserModel())->find($token->email))->image;
            }
            else
            {
                header("Location: /HomeController");
                exit();
            }
        }
    }

    // Only lets through guests and registered users.
    private function onlyBasicAccounts()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null && isAuthenticated("RUser"))
            {
                $this->userMail = $token->email;
                $this->userName = $token->firstName." ".$token->lastName;
                $this->userImage = ((new UserModel())->find($token->email))->image;
            }
            else
            {
                header("Location: /HomeController");
                exit();
            }
        }
    }
    
    // Checks if the request is from a logged in Cinema account.
    private function goHomeIfNotCinema()
    {
        helper("auth");

        if (isset($_COOKIE["token"]))
        {
            $tokenCookie = $_COOKIE["token"];
            $token = isValid($tokenCookie);
            
            if ($token != null && isAuthenticated("Cinema"))
            {
                $this->userMail = $token->email;
                $this->userName = $token->name;
                $this->userImage = ((new UserModel())->find($token->email))->image;
                return;
            }
        }
        header("Location: /HomeController");
        exit();
    }

    // Checks if the request is of POST type and if it's not, reroutes home.
    private function goHomeIfNotPost()
    {
        if($_SERVER["REQUEST_METHOD"] != "POST") {
            // Unauthorized GET request
            header("Location: /Cinema");
            exit();
        }
    }
}