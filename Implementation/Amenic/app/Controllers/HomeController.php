<?php namespace App\Controllers;

/*
    Author: Martin Mitrović
    Github: Rpsaman13000
*/
use \App\Models\UserModel;
use \App\Models\CountryModel;
use \App\Models\CityModel;
use \App\Models\RUserModel;

use App\Models\MovieModel;
use App\Models\ComingSoonModel;
use App\Models\CinemaModel;
use App\Models\ProjectionModel;

use function App\Helpers\isAuthenticated;
use function App\Helpers\isValid;
use function App\Helpers\generateToken;
use function App\Helpers\setToken;


/** HomeController – starting class which takes care of guests and registered users
 *  -provides list of all awailable movies
 *  -provides list of all awailable cinemas
 *  -registered users can change account information 
 *  @version 1.0
 */
class HomeController extends BaseController
{
	/** Function which returns movies currently playing
     * @return array[Movie] list of available movies
     */
	private function getPlayingMovies()
	{
		$movieArray=[];
		$projections = (new ProjectionModel())->select('tmdbID')->groupBy('tmdbID')->findAll();
		
		foreach($projections as $projection)
		{
			$movie = (new MovieModel())->find($projection->tmdbID);
			array_push($movieArray,$movie);
			
		}
		return $movieArray;
	}

	/** Function which returns movies marked as coming soon
     * @return array[Movie] list of available movies
     */
	private function getComingSoonMovies()
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->findAll();	

		foreach($comingSoon as $movie)
		{
			$curMovie = (new MovieModel())->find($movie->tmdbID);
			if (!isset($movieArray[$curMovie->tmdbID]))
				$movieArray[$curMovie->tmdbID] = $curMovie;
		}
		return $movieArray;
	}

	public function index()
	{
		$movieArray = $this->getPlayingMovies();	
		return view('index.php',[ 'movies' => $movieArray, 'actMenu' => 1]);
	}

	public function comingSoon()
	{
		$movieArray = $this->getComingSoonMovies();
		return view('index.php',[ 'movies' => $movieArray, 'actMenu' => 2]);
	}

	private function getWantedPlayingMovies($title)
	{
		$movieArray=[];
		$movies = (new MovieModel())
			->like('title',$title, $insensitiveSearch = TRUE)
			->findAll();
		foreach($movies as $movie)
		{
			$curMovie = (new ProjectionModel())->where(['tmdbID' => $movie->tmdbID])->find();
			if (count($curMovie) > 0)
				array_push($movieArray,$movie);
		}
		return $movieArray;
	}

	private function getWantedComingSoonMovies($title)
	{
		$movieArray=[];
		$comingSoon = (new ComingSoonModel())->select('tmdbID')->groupBy('tmdbID')->findAll();	
		
		foreach($comingSoon as $movie)
		{
			$projection = (new ProjectionModel())->where('tmdbID',$movie->tmdbID)->findAll();
			if (count($projection) == 0)
			{
				$curMovie = (new MovieModel())->like('title',$title, $insensitiveSearch = TRUE)->find($movie->tmdbID);
				if (!is_null($curMovie))
					array_push($movieArray,$curMovie);
			}
		}
	
		return $movieArray;
	}

	public function titleSearch()
	{
		$menu = $_GET['actMenu'];
		unset($_GET['actMenu']);
		$title = $_GET['title'];
		unset($_GET['title']);

		if(strcmp($menu,"1") == 0)
		{
			$movieArray = $this->getWantedPlayingMovies($title);
		}
		else
		{
			$movieArray = $this->getWantedComingSoonMovies($title);
		}
		
		return json_encode($movieArray);
		//return view('index.php',[ 'movies' => $movieArray, 'actMenu' => $menu]);
	}

	public function cinemas()
	{
		$cinemaArray = (new CinemaModel())->where(['approved' => 1])->findAll();	
		return view('index.php',[ 'movies' => $cinemaArray, 'cinMenu' => 1]);
	}

	public function nameSearch()
	{
		$title = $_GET['title'];
		unset($_GET['title']);

		$cinemaArray=[];
		$cinemas = (new CinemaModel())
			->where('approved',1)	
			->like('name',$title, $insensitiveSearch = TRUE)->find();
	
		return json_encode($cinemas);
	}
	
	private function getToken()
    {
        helper('auth');

        if (isset($_COOKIE['token']))
        {
            $tokenCookie = $_COOKIE['token'];   
            $token = isValid($tokenCookie);
            
            if ($token && isAuthenticated("RUser"))
            {
                $image = (new UserModel())->find($token->email);
                $token->image = $image->image;

                return $token;
            }
        }
        return null;
	}

	public function getCities($countryId)
	{
		if (is_null($countryId))
			return null;
		return json_encode((new CityModel())->where('idCountry',$countryId)->find());
	}
	
	//Settings function
    /** Funkcija koja adminu prikazuje formu kojom može da menja svoje podatke osim email adrese
     * @return view SettingsView sa podacima [$podaciZaPrikazivanje, $trenutnoAktivniMeni, $slikaKorisnika, $tipKorisnika]
     */
    public function settings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);
            
		$countries = (new CountryModel())->findAll();
		if (!is_null($token->city))
			$cities = (new CityModel())->where('idCountry',$token->country)->find();
		else
			$cities = null;
        
        $data = [
            'firstName' => $token->firstName,
            'lastName' => $token->lastName,
			'email' =>  $token->email,
			'phone' => $token->phone,
			'userCountry' => $token->country,
			'userCity' => $token->city,
			'countries' => $countries,
			'cities' => $cities
            ];
        
        //you have to save admin info twice beacause this page is being used by all users
        return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'RUser', 'token' => $token, 'errors' => '' ]);    
    }

    public function saveSettings()
    {
        $token = $this->getToken();
        if (is_null($token))
            return view('AdminBreachMessage',[]);        

        $validation =  \Config\Services::validation();
        $db = db_connect();

        //fetching data
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $token->email;
        $city = $_POST['city'];
        $country = $_POST['country'];
        $phone = $_POST['phone'];
        $pswdOld = $_POST['pswdOld'];
        $pswdNew = $_POST['pswdNew'];
        $image = $this->request->getFile('profilePicture');
        
        $form = [
            'fName' => $fName,
            'lName' => $lName,
            'email' => $email,
            'phone' => $phone,
            'place' => [
                'country' => $country,
                'city' => $city
            ],
            'pswd' => [ 
                'oldPswd' => $pswdOld,
                'newPswd' => $pswdNew,
                'email' => $email
            ],
            'profilePicture' => $image
        ]; 

        $valid = $validation->run($form, "rUserAccountCheck");
        $validPlace = $validation->run($form, "placeCheck");

        if($valid != 1 || $validPlace != 1)
        {
            $countries = (new CountryModel())->findAll();
            $cities = (new CityModel())->where('idCountry',$token->country)->find();
            
            $data = [
                'firstName' => $token->firstName,
                'lastName' => $token->lastName,
                'email' =>  $token->email,
                'phone' => $token->phone,
                'userCountry' => $token->country,
                'userCity' => $token->city,
                'countries' => $countries,
                'cities' => $cities
            ];

            $errors = $validation->getErrors();

            return view('SettingsView',['data' => $data, 'actMenu' => 5, 'image' => $token->image, 'userType' => 'RUser', 'token' => $token, 'errors' => $errors ]);    
        }
        
        //password remains the same
        if(strcmp($pswdNew,"") == 0)
        {
            $pswdNew = (new UserModel())->find($email)->password;
        }
        else
        {
            $pswdNew = password_hash($pswdNew,PASSWORD_BCRYPT, ['cost' => 8]);
        }
        
        try
        {
            //update database
            $db->transCommit();
            $img=null;
            if (strcmp($image->getName(),"") !=0)
            {
                $img = base64_encode(file_get_contents($image));
                (new UserModel())->where(['email' => $email])->set([
                    'image' => $img,             
                    ])->update();            
            }

            (new UserModel())->where(['email' => $email])->set([
                'password' => $pswdNew               
                ])->update();
            
            $country = strcasecmp($country,"0") == 0 ? NULL : $country;
            $city = strcasecmp($city,"0") == 0 ? NULL : $city;

            (new RUserModel())->where(['email' => $email])->set(['fName' => $fName, 'lName' => $lName, 'phoneNumber' => $phone, 'idCountry' => $country,'idCity' => $city])->update();
            $db->transCommit();
        }
        catch (Exception $e)
        {
            $db->transRollback();
            throw new Exception("Transaction ".get_class($this).".saveChanges(".$email.") failed!<br/>".$e->getMessage());
        }

        //change token
        $payload = [
            'firstName' => $fName,
            'lastName' => $lName,
            "email" => $email,
            'phone' => $phone,
            'country' => $country,
            'city' => $city,
            "type" => "RUser"
        ]; 

        setToken(generateToken($payload));
        $token = $this->getToken();

        return $this->index();
    }

}
