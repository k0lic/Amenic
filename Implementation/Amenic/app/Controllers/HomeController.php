<?php namespace App\Controllers;

use App\Models\MovieModel;

class HomeController extends BaseController
{
	public function index()
	{
		$movies = (new MovieModel())->findAll();
		return view('index.php',[ 'movies' => $movies]);
	}

	//connect to a database
	/*$db = \Config\Database::connect());
	$db->query('select * from Admins');
	$sql = "select * from Admins where email LIKE :email: AND firstName LIKE :firstName:";
	$db->query($sql,['email' => 'adas', 'firstName' => 'asdas']);
	$db->error();
	prepar(query) pa db->is_executable
	zatvori svaki prepare

	$builder = $model->builder();
	QueryBuilder!
	insert,update, save-radi isto sto i dve prve fje zajedno, delete


	getResult() za uz foreach($query->getResult())
	getRow(numRow);
	$db->countAll();
	*/

}
