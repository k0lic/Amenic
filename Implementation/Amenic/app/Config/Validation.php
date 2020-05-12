<?php namespace Config;

/*
	Author: Miloš Živkovic
    Github: zivkovicmilos
*/

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
		\App\Rules\CustomRules::class
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	
	public $p2Cinema = [
		'cinemaName' => 'required|min_length[5]',
		'address' => 'required|min_length[5]',
		'phoneNumber' => 'required|min_length[4]',
		'country' => 'required',
		'city' => 'required',
		'description' => 'permit_empty|min_length[10]|max_length[255]'
	];

	public $p2Cinema_errors = [
		'cinemaName' => [
			'required' => 'Cinema name is required',
			'min_length[5]' => 'Cinema name is too short',
		],
		'address' => [
			'required' => 'Address is required',
			'min_length[5]' => 'Address is too short',
		],
		'phoneNumber' => [
			'required' => 'Phone number is required',
			'min_length[4]' => 'Phone number is too short',
		],
		'country' => [
			'required' => 'Country is required',
		],
		'city' => [
			'required' => 'City is required',
		],
		'description' => [
			'min_length[10]' => 'Description must be at least 10 characters long',
			'max_length[255]' => 'Description is longer than 255 characters',
		],

	];

	public $p3Cinema = [
		'mngFirstName' => 'required|alpha',
		'mngLastName' => 'required|alpha',
		'mngEmail' => 'required|valid_email',
		'mngPhoneNumber' => 'required|numeric|min_length[4]'
	];

	public $p3Cinema_errors = [
		'mngFirstName' => [
			'required' => 'First name is required',
			'alpha' => 'Field must contain only letters'
		],

		'mngLastName' => [
			'required' => 'Last name is required',
			'alpha' => 'Field must contain only letters'
		],
		'mngEmail' => [
			'required' => 'Email is required',
			'valid_email' => 'Email must be valid',
		],
		'mngPhoneNumber' => [
			'required' => 'Phone number is required',
			'min_length[4]' => 'Phone number is too short',
			'numeric' => 'Must contain only numbers'
		]
	];

	public $p2User = [
		'firstName' => 'required|alpha',
		'lastName' => 'required|alpha',
		'email' => 'required|valid_email',
		'phone' => 'permit_empty|numeric|min_length[4]',
		'country.*' => 'required',
		'city.*' => 'required',
	];

	public $p2User_errors = [
		'firstName' => [
			'required' => 'First name is required',
			'alpha' => 'Field must contain only letters'
		],

		'lastName' => [
			'required' => 'Last name is required',
			'alpha' => 'Field must contain only letters'
		],
		'email' => [
			'required' => 'Email is required',
			'valid_email' => 'Email must be valid',
		],
		'phone' => [
			'min_length[4]' => 'Phone number is too short',
			'numeric' => 'Must contain only numbers'
		],
		'country.*' => [
			'required' => 'Country is required'
		],
		'city.*' => [
			'required' => 'City is required'
		]
	];

	public $passwordCheck = [
		'firstPassword' => 'required|min_length[6]',
		'secondPassword' => 'required|matches[firstPassword]'
	];

	public $passwordCheck_errors = [
		'firstPassword' => [
			'required' => 'Password is required',
			'min_length' => 'Password must be at least 6 characters long'
		],
		'secondPassword' => [
			'required' => 'Password is required',
			'matches' => 'Passwords must match'
		]
	];

	public $adminAccountCheck = [
		'fNameNA' => 'required|alpha',
		'lNameNA' => 'required|alpha',
		'emailNA' => 'required|valid_email',
		'passwordNA' => 'required|min_length[6]',
		'passwordConfirmNA' => 'required|matches[passwordNA]'
	];

	public $adminAccountCheck_errors =[
		'fNameNA' => [
			'required' => 'First name is required',
			'alpha' => 'Field must contain only letters'
		],

		'lNameNA' => [
			'required' => 'Last name is required',
			'alpha' => 'Field must contain only letters'
		],
		'emailNA' => [
			'required' => 'Email is required',
			'valid_email' => 'Email must be valid',
		],
		'passwordNA' => [
			'required' => 'Password is required',
			'min_length' => 'Password must be at least 6 characters long'
		],
		'passwordConfirmNA' => [
			'required' => 'Password is required',
			'matches' => 'Passwords must match'
		]
	];

	// Cinema controller validation.

	public $actionAddRoom = [
		'roomName' => [
			'label' => 'Name',
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[64]|checkRoomName'
		],
		'tech' => [
			'label' => 'Technologies',
			'rules' => 'required|checkRoomTech'
		],
		'rows' => [
			'label' => 'Number of rows',
			'rules' => 'integer|greater_than[0]|less_than[27]'
		],
		'columns' => [
			'label' => 'Number of seats in each row',
			'rules' => 'integer|greater_than[0]|less_than[27]'
		]
	];
	
	public $actionEditRoom = [
		'oldRoomName' => [
			'label' => 'Old name',
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[64]|checkOldRoomName'
		],
		'roomName' => [
			'label' => 'Name',
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[64]|checkRoomNameExcept'
		],
		'tech' => [
			'label' => 'Technologies',
			'rules' => 'required|checkRoomTech'
		],
		'rows' => [
			'label' => 'Number of rows',
			'rules' => 'integer|greater_than[0]|less_than[27]'
		],
		'columns' => [
			'label' => 'Number of seats in each row',
			'rules' => 'integer|greater_than[0]|less_than[27]'
		]
	];

	public $actionRemoveRoom = [
		'oldRoomName' => [
			'label' => 'Old name',
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[64]|checkOldRoomName'
		]
	];

	public $actionAddMovie = [
		'tmdbID' => [
			'label' => 'Movie ID',
			'rules' => 'required|integer|max_length[64]|is_not_unique[movies.tmdbID]'
		],
		'room' => [
			'label' => 'Room',
			'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[64]|checkOldRoomName'
		],
		'tech' => [
			'label' => 'Technology',
			'rules' => 'required|integer|checkMovieTech'
		],
		'startDate' => [
			'label' => 'Date of projection',
			'rules' => 'required|valid_date[Y-m-d]'					// check if not in the past
		],
		'startTime' => [
			'label' => 'Start time',
			'rules' => 'required|exact_length[5]|validateTime'		// check if not in the past, check if no collisions
		],
		'price' => [
			'label' => 'Price',
			'rules' => 'required|decimal'
		]
	];

	/*
	movieName,tmdbID,room,tech,startDate,startTime,price,soon			- AddMovie
	oldIdPro															- Edit movie
	oldtmdbID															- Release soon
	*/

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
}
