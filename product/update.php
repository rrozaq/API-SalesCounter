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

// instantiate customer object
include_once '../objects/product.php';
include_once '../objects/varian_stock.php';
	
$database = new Database();
$db = $database->getConnection();

// prepare news object
$product = new Product($db);
$varianStock = new VarianStock($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set ID property of news to be edited

$image1 = $data->image;
list($type, $image1) = explode(';', $image1);
list(, $image1)      = explode(',', $image1);
$data_image1 = base64_decode($image1);
$mime_type1 = "." . $data->ext;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$product->id = $data->id;
$product->product_code = $data->product_code;
$product->title = $data->title;
$product->remarks = $data->remarks;

if($data_image1 != null){
	$product->image 		= $data->product_code.$mime_type1;
}else{
	$product->image 		= '';
}

$product->date_time = $data->date_time;

$imageDir = "{$upload_image_url}" .'/';

if($data_image1 != null){
	file_put_contents($imageDir . $product->image, $data_image1);
}

// $product->id_varian = $data->id_varian;
// $product->stock = $data->stock;

$product->update();
$currentId = $db->lastInsertId();
// mkdir("{$upload_image_url}", 0777, true);
// file_put_contents("{$upload_image_url}" . $data->imageName, $data_image1);

foreach($data->stock as $stockItem) {
	// set contact property values
	$varianStock->id 			= $stockItem->id;
	$varianStock->id_product 	= $stockItem->id_product;
	$varianStock->id_varian 	= $stockItem->id_varian;
	$varianStock->stock 		= $stockItem->stock;
	
	$varianStock->update();
	$currentVarianId = $db->lastInsertId();

// update the news
if($varianStock->update()){
	echo '{';
		echo '"message": "Product was updated."';
	echo '}';
}

// if unable to update the news, tell the user
else{
	echo '{';
		echo '"message": "Unable to update product."';
	echo '}';
}
}
?>