<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate varianstock object
include_once '../objects/varian_stock.php';

$database = new Database();
$db = $database->getConnection();
	
$varian_stock = new VarianStock($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set varianstock property values
$varian_stock->id_product = $data->id_product;
$varian_stock->id_varian = $data->id_varian;
$varian_stock->stock = $data->stock;

$varian_stock_arr = array(
	"id_product" => $varian_stock->id_product,
	"id_varian" => $varian_stock->id_varian,
	"stock" => $varian_stock->stock	
);

// create the varianstock
if($varian_stock->create()){
	echo json_encode($varian_stock_arr);
}

// if unable to create the varianstock, tell the varianstock
else{
	echo '{';
		echo '"message": "Unable to create varianstock."';
	echo '}';
}
?>