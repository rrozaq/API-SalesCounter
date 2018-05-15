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

$dataProduct = new Product($db);
$dataVarianStock = new VarianStock($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$image1 = $data->image;
list($type, $image1) = explode(';', $image1);
list(, $image1)      = explode(',', $image1);
$data_image1 = base64_decode($image1);
$mime_type1 = "." . $data->ext;


///////////////////////////////////////////////////////////////////////////
$dataProduct->product_code = $data->product_code;
$dataProduct->title = $data->title;
$dataProduct->remarks = $data->remarks;

if($data_image1 != null){
	$dataProduct->image 		= $data->product_code.$mime_type1;
}else{
	$dataProduct->image 		= '';
}

$dataProduct->date_time = $data->date_time;

$imageDir = "{$upload_image_url}" .'/';

if($data_image1 != null){
	file_put_contents($imageDir . $dataProduct->image, $data_image1);
}

$dataProduct->id_varian = $data->id_varian;
$dataProduct->stock = $data->stock;

$dataProduct->create();
$currentProductId = $db->lastInsertId();

$product_arr = array(
	"id" => $currentProductId,
	"product_code" => $dataProduct->product_code,
	"title" => $dataProduct->title,
	"remarks" => $dataProduct->remarks,
	"image" => "{$data_image_url}" . $dataProduct->image,
	"date_time" => $dataProduct->date_time
);
$response  = $product_arr;

///////////////////////////////////////////////////////////////////////////
// $dataProduct->id = $currentProductId;
// $dataProduct->productCode = $currentProductId;
// $dataProduct->updateProductCode();

///////////////////////////////////////////////////////////////////////////
//base64 - save image to directory
// $image1 = $data->photo1;
// $image2 = $data->photo2;
// $image3 = $data->photo3;

// list($type, $image1) = explode(';', $image1);
// list(, $image1)      = explode(',', $image1);
// $data_image1 = base64_decode($image1);
// $mime_type1 = "." . $data->photo1Ext;

// list($type, $image2) = explode(';', $image2);
// list(, $image2)      = explode(',', $image2);
// $data_image2 = base64_decode($image2);
// $mime_type2 = "." . $data->photo2Ext;

// list($type, $image3) = explode(';', $image3);
// list(, $image3)      = explode(',', $image3);
// $data_image3 = base64_decode($image3);
// $mime_type3 = "." . $data->photo3Ext;

foreach($data->stock as $stockItem) {
	// set contact property values
	$dataVarianStock->id_product 	= $currentProductId;
	$dataVarianStock->id_varian 	= $stockItem->id_varian;
	$dataVarianStock->stock 		= $stockItem->stock;
	
	$dataVarianStock->create();
	$currentVarianId = $db->lastInsertId();

	
	// create array
	$varianstock_arr = array(
		"id_product" => $currentProductId,
		"id_varian" => $dataVarianStock->id_varian,
		"stock" => $dataVarianStock->stock
	);
	$response['varian_stock']  = $varianstock_arr;

}

///////////////////////////////////////////////////////////////////////////

// if($data_image1 != null){
// 	$dataPopulation->photo1 = 'photo1'. $mime_type1;
// }else{
// 	$dataPopulation->photo1 = '';
// }

// if($data_image2 != null){
// 	$dataPopulation->photo2 = 'photo2'. $mime_type2;
// }else{
// 	$dataPopulation->photo2 = '';
// }

// if($data_image3 != null){
// 	$dataPopulation->photo3 = 'photo3'. $mime_type3;
// }else{
// 	$dataPopulation->photo3 = '';
// }


// mkdir("{$upload_varian_stock_file}" .'/'. $currentVarianId , 0777, true);


///////////////////////////////////////////////////////////////////////////
// mkdir("{$upload_population_file}" .'/'. $currentPopulationId , 0777, true);


// $imageDir = "{$upload_population_file}" .'/'. $currentPopulationId . '/';

// $finalImageUrl = "{$population_url}" .'/'. $currentPopulationId . '/';

// if($data_image1 != null){
// 	file_put_contents($imageDir . 'photo1' . $mime_type1, $data_image1);
// }

// if($data_image2 != null){
// 	file_put_contents($imageDir . 'photo2'. $mime_type2, $data_image2);
// }

// if($data_image2 != null){
// 	file_put_contents($imageDir . 'photo2'. $mime_type2, $data_image2);
// }

// if($data_image3 != null){
// 	file_put_contents($imageDir . 'photo3'. $mime_type3, $data_image3);
// }



///////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
// create array


// create array

print_r(json_encode($response));

?>