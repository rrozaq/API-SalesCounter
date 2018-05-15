<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
include_once '../config/core.php';

// instantiate product object
include_once '../objects/order.php';
include_once '../objects/order_detail.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$order_detail = new OrderDetail($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// $image1 = $data->image;
// list($type, $image1) = explode(';', $image1);
// list(, $image1)      = explode(',', $image1);
// $data_image1 = base64_decode($image1);
// $mime_type1 = "." . $data->ext;


// set product property values
$order->sales_id 	= $data->sales_id;
$order->date_time 	= $data->date_time;
$order->keterangan	= $data->keterangan;
$order->tujuan		= $data->tujuan;


// if($data_image1 != null){
// 	$product->image 		= $data->product_code.$mime_type1;
// }else{
// 	$product->image 		= '';
// }



// $imageDir = "{$upload_image_url}" .'/';

// if($data_image1 != null){
// 	file_put_contents($imageDir . $product->image, $data_image1);
// }

$order->create();
$orderCurrentId = $db->lastInsertId();

$order_detail->order_id 	= $orderCurrentId;
$order_detail->product_code = $data->product_code;
$order_detail->id_varian 	= $data->id_varian;
$order_detail->qty 			= $data->qty;

$order_detail->create();
$detailCurrentId = $db->lastInsertId();

// create array
$order_detail_arr = array(
	"order_id" 	=> $orderCurrentId,
	"product_code" 	=> $order_detail->product_code,
	"id_varian" 		=> $order_detail->id_varian,
	"qty"		=> $order_detail->qty
);

//array_push($categories_arr["records"], $submarket_arr);

$response[]['order_detail'] = $order_detail_arr;


// create array

$order_arr = array(
	"sales_id" => $order->sales_id,
	"date_time" => 	$order->date_time,
	"keterangan" => $order->keterangan,
	"tujuan" => $order->tujuan
);

$response[]['order']  = $order_arr;

echo json_encode($response);

?>