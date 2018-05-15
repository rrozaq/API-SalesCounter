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
include_once '../objects/order_detail.php';

$database = new Database();
$db = $database->getConnection();

$order_detail = new OrderDetail($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set varianstock property values
$order_detail->order_id = $data->order_id;
$order_detail->product_code = $data->product_code;
$order_detail->id_varian = $data->id_varian;
$order_detail->qty = $data->qty;


$order_detail_arr = array(
	"order_id" => $order_detail->order_id,
	"product_code" => $order_detail->product_code,
	"id_varian" => $order_detail->id_varian,
	"qty" => $order_detail->qty	
	
);

// create the varianstock
if($order_detail->create()){
	echo json_encode($order_detail_arr);
}

// if unable to create the varianstock, tell the varianstock
else{
	echo '{';
		echo '"message": "Unable to create orderdetail."';
	echo '}';
}
?>