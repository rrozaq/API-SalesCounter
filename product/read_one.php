<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';
include_once '../config/core.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$product = new Product($db);

// set ID property of product to be edited
$product->product_code = isset($_GET['product_code']) ? $_GET['product_code'] : die();

// read the details of product to be edited
$product->readOne();

// create array
$product_arr = array(
	"id" =>  $product->id,
	"product_code" => $product->product_code,
	"title" => $product->title,
	"remarks" => $product->remarks,
	"image" => $data_image_url.$product-> image,
	"date_time" => $product->date_time

);

// make it json format
print_r(json_encode($product_arr));
?>