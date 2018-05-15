<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate varian object
include_once '../objects/varian.php';

$database = new Database();
$db = $database->getConnection();

$varian = new Varian($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set varian property values
$varian->id_category 		= $data->id_category;
$varian->name 				= $data->name;

$varian_arr = array(
	"id_category" 	=> $varian->id_category,
	"name" 			=> $varian->name
);

// create the varian
if($varian->create()){
	echo json_encode($varian_arr);
}

// if unable to create the varian, tell the varian
else{
	echo '{';
		echo '"message": "Unable to create varian."';
	echo '}';
}
?>