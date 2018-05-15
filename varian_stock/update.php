<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/varian.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare varian object
$varian = new Varian($db);

// get id of varian to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of varian to be edited
$varian->id = $data->id;

// set varian property values
$varian->name = $data->name;
$varian->id_category = $data->id_category;

$varian_arr = array(
	"id" => $varian->id,
	"id_category" => $varian->id_category,
	"name" => $varian->name
);

// update the varian
if($varian->update()){
	echo json_encode($varian_arr);
}

// if unable to update the varian, tell the varian
else{
	echo '{';
		echo '"message": "Unable to update varian."';
	echo '}';
}
?>