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
		'phoneNumber' => 'required|min_length[5]',
		'country' => 'required',
		'city' => 'required',
		'description' => 'min_length[10]'
	];

	public $p3Cinema = [
		'mngFirstName' => 'required',
		'mngLastName' => 'required',
		'mngEmail' => 'required|valid_email',
		'mngPhoneNumber' => 'required|min_length[5]'
	];

	public $p2User = [
		'firstName' => 'required',
		'lastName' => 'required',
		'email' => 'required|valid_email',
		'phoneNumber' => 'min_length[5]',
		'country' => 'required',
		'city' => 'required'
	];

	public $passwordCheck = [
		'firstPassword' => 'required|min_length[6]',
		'secondPassword' => 'required|matches[firstPassword]'
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
}
