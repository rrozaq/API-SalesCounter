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
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$image1 = $data->image;
list($type, $image1) = explode(';', $image1);
list(, $image1)      = explode(',', $image1);
$data_image1 = base64_decode($image1);
$mime_type1 = "." . $data->ext;


// set product property values
$product->product_code 	= $data->product_code;
$product->title 		= $data->title;
$product->remarks 		= $data->remarks;
$product->id_varian 	= $data->id_varian;
$product->stock 		= $data->stock;

if($data_image1 != null){
	$product->image 		= $data->product_code.'_'.$data->date_time.$mime_type1;
}else{
	$product->image 		= '';
}

$product->date_time 	= $data->date_time;


$imageDir = "{$upload_image_url}" .'/';

if($data_image1 != null){
	file_put_contents($imageDir . $product->image, $data_image1);
}

// create array
$response= array();
$user_arr = array(
	"product_code" => $product->product_code,
	"title" => $product->title,
	"remarks" => $product->remarks,
	"id_varian" => $product->id_varian,
	"stock" => $product->stock,
	"image" => "{$data_image_url}" . $product->image,
	"date_time" => $product->date_time
);


$response = $user_arr;
// create the product
if($product->create()){
	// make it json format
	print_r(json_encode($response));
}
// if unable to create the product, tell the user
else{
	echo '{';
		echo '"message": "Unable to create product."';
	echo '}';
}
?>