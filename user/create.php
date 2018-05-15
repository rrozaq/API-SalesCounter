<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate user object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values

$user->password 	= $data->password;
$uuid 				= uniqid('', true);
$hash 				= $user->hashSSHA($user->password);
$encrypted_password = $hash["encrypted"]; // encrypted password
$salt 				= $hash["salt"]; // salt

$user->unique_id 			= $uuid;
$user->name 				= $data->name;
$user->email 				= $data->email;
$user->encrypted_password 	= $encrypted_password;
$user->salt 				= $salt;
$user->token 				= $data->token;
$user->level 				= $data->level;

$user_arr = array(
	"name" 			=> $user->name,
	"email" 		=> $user->email,
	"token" 		=> $user->token,
	"level" 		=> $user->level
);

// create the user
if($user->create()){
	echo json_encode($user_arr);
}

// if unable to create the user, tell the user
else{
	echo '{';
		echo '"message": "Unable to create user."';
	echo '}';
}
?>