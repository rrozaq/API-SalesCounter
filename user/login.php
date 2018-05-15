<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../config/core.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// set ID property of product to be edited
$user->email = isset($_GET['email']) ? $_GET['email'] : die();
$user->password = isset($_GET['password']) ? $_GET['password'] : die();

// read the details of product to be edited
$user->login();
//$user->getSalt();

// create array
$user_arr = array(
	"id" => $user->id,
	"name" => $user->name,
	"email" => $user->email,
	"level" => $user->level
);

// make it json format
print_r(json_encode($user_arr));
	
?>