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
include_once '../objects/order.php';
include_once '../objects/order_detail.php';

$database = new Database();
$db = $database->getConnection();

$dataOrder = new Order($db);
$dataOrderDetail = new OrderDetail($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// $image1 = $data->image;
// list($type, $image1) = explode(';', $image1);
// list(, $image1)      = explode(',', $image1);
// $data_image1 = base64_decode($image1);
// $mime_type1 = "." . $data->ext;


///////////////////////////////////////////////////////////////////////////
$dataOrder->sales_id = $data->sales_id;
$dataOrder->date_time = $data->date_time;
$dataOrder->keterangan = $data->keterangan;
$dataOrder->tujuan	= $data->tujuan;


$dataOrder->product_code = $data->product_code;
$dataOrder->id_varian = $data->id_varian;
$dataOrder->qty = $data->qty;

$dataOrder->create();
$currentOrderId = $db->lastInsertId();

$order_arr = array(
	"id" => $currentOrderId,
	"sales_id" => $dataOrder->sales_id,
	"date_time" => $dataOrder->date_time,
	"keterangan" => $dataOrder->keterangan,
	"tujuan" => $dataOrder->tujuan
);
$response  = $order_arr;

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

foreach($data->orderdetail as $detailItem) {
	// set contact property values
	$dataOrderDetail->order_id 	= $currentOrderId;
	$dataOrderDetail->product_code 	= $detailItem->product_code;
	$dataOrderDetail->id_varian 		= $detailItem->id_varian;
    $dataOrderDetail->qty 		= $detailItem->qty;
    
	$dataOrderDetail->create();
	$currentOrderDetailId = $db->lastInsertId();

	
	// create array
	$orderdetail_arr = array(
		"order_id" => $currentOrderId,
		"product_code" => $dataOrderDetail->product_code,
		"id_varian" => $dataOrderDetail->id_varian,
		"qty" => $dataOrderDetail->qty        
    );
	$response['order_detail']  = $orderdetail_arr;

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