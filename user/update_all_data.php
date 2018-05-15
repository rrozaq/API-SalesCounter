<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// get id of user to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of user to be edited
$user->id 			= $data->id;

$user->password 	= $data->password;
$uuid 				= uniqid('', true);
$hash 				= $user->hashSSHA($user->password);
$encrypted_password = $hash["encrypted"]; // encrypted password
$salt = $hash["salt"]; // salt

// set user property values
$user->unique_id 			= $uuid;
$user->name 				= $data->name;
$user->email 				= $data->email;
$user->encrypted_password 	= $encrypted_password;
$user->salt 				= $salt;
$user->level 				= $data->level;

$user_arr = array(
	"name" 			=> $user->name,
	"email" 		=> $user->email,
	"level" 		=> $user->level
);

// update the user
if($user->updateAllData()){
	echo json_encode($user_arr);
}

// if unable to update the user, tell the user
else{
	echo '{';
		echo '"message": "Unable to update user."';
	echo '}';
}
?>