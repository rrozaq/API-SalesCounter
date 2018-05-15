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
include_once '../objects/population.php';

$database = new Database();
$db = $database->getConnection();

$dataProduct = new Product($db);
$dataPopulation = new Population($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));


///////////////////////////////////////////////////////////////////////////
$dataProduct->brandId = $data->brandId;
$dataProduct->brandGroupId = $data->brandGroupId;
$dataProduct->brandSubGroupId = $data->brandSubGroupId;
$dataProduct->brandTypeId = $data->brandTypeId;
$dataProduct->brandModelId = $data->brandModelId;
$dataProduct->serialNo = $data->serialNo;
$dataProduct->sealNo = $data->sealNo;

$dataProduct->createdBy = $data->createdBy;
$dataProduct->createdAt = $data->createdAt;
$dataProduct->updateBy = $data->updateBy;
$dataProduct->updateAt = $data->updateAt;
$dataProduct->approveBy = $data->approveBy;
$dataProduct->approveAt = $data->approveAt;
$dataProduct->updateFrom = $data->updateFrom;

$dataProduct->createProduct();
$currentProductId = $db->lastInsertId();

///////////////////////////////////////////////////////////////////////////
$dataProduct->id = $currentProductId;
$dataProduct->productCode = $currentProductId;
$dataProduct->updateProductCode();

///////////////////////////////////////////////////////////////////////////
//base64 - save image to directory
$image1 = $data->photo1;
$image2 = $data->photo2;
$image3 = $data->photo3;

list($type, $image1) = explode(';', $image1);
list(, $image1)      = explode(',', $image1);
$data_image1 = base64_decode($image1);
$mime_type1 = "." . $data->photo1Ext;

list($type, $image2) = explode(';', $image2);
list(, $image2)      = explode(',', $image2);
$data_image2 = base64_decode($image2);
$mime_type2 = "." . $data->photo2Ext;

list($type, $image3) = explode(';', $image3);
list(, $image3)      = explode(',', $image3);
$data_image3 = base64_decode($image3);
$mime_type3 = "." . $data->photo3Ext;

///////////////////////////////////////////////////////////////////////////
$dataPopulation->companyId = $data->companyId;
$dataPopulation->endUserId = $data->endUserId;
$dataPopulation->productId = $currentProductId;
$dataPopulation->id = $data->id;
$dataPopulation->suppliedBy = $data->suppliedBy;
$dataPopulation->dateSupplied = $data->dateSupplied;
$dataPopulation->tagNo = $data->tagNo;
$dataPopulation->service = $data->service;
$dataPopulation->factoryRef = $data->factoryRef;
$dataPopulation->salesId = $data->salesId;
$dataPopulation->dimRef = $data->dimRef;

if($data_image1 != null){
	$dataPopulation->photo1 = 'photo1'. $mime_type1;
}else{
	$dataPopulation->photo1 = '';
}

if($data_image2 != null){
	$dataPopulation->photo2 = 'photo2'. $mime_type2;
}else{
	$dataPopulation->photo2 = '';
}

if($data_image3 != null){
	$dataPopulation->photo3 = 'photo3'. $mime_type3;
}else{
	$dataPopulation->photo3 = '';
}

$dataPopulation->latitude = $data->latitude;
$dataPopulation->longitude = $data->longitude;
$dataPopulation->createdBy = $data->createdBy;
$dataPopulation->createdAt = $data->createdAt;
$dataPopulation->updateBy = $data->updateBy;
$dataPopulation->updateAt = $data->updateAt;
$dataPopulation->approveBy = $data->approveBy;
$dataPopulation->approveAt = $data->approveAt;
$dataPopulation->updateFrom = $data->updateFrom;

$dataPopulation->createPopulation();
$currentPopulationId = $db->lastInsertId();

///////////////////////////////////////////////////////////////////////////
mkdir("{$upload_population_file}" .'/'. $currentPopulationId , 0777, true);


$imageDir = "{$upload_population_file}" .'/'. $currentPopulationId . '/';

$finalImageUrl = "{$population_url}" .'/'. $currentPopulationId . '/';

if($data_image1 != null){
	file_put_contents($imageDir . 'photo1' . $mime_type1, $data_image1);
}

if($data_image2 != null){
	file_put_contents($imageDir . 'photo2'. $mime_type2, $data_image2);
}

if($data_image2 != null){
	file_put_contents($imageDir . 'photo2'. $mime_type2, $data_image2);
}

if($data_image3 != null){
	file_put_contents($imageDir . 'photo3'. $mime_type3, $data_image3);
}



///////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
// create array
$product_arr = array(
	"id" => $currentProductId,
	"productCode" => $dataProduct->productCode,
	"brandId" => $dataProduct->brandId,
	"brandGroupId" => $dataProduct->brandGroupId,
	"brandSubGroupId" => $dataProduct->brandSubGroupId,
	"brandTypeId" => $dataProduct->brandTypeId,
	"brandModelId" => $dataProduct->brandModelId,
	"serialNo" => $dataProduct->serialNo,
	"sealNo" => $dataProduct->sealNo,
	"createdBy" => $dataProduct->createdBy,
	"createdAt" => $dataProduct->createdAt,
	"updateBy" => $dataProduct->updateBy,
	"updateAt" => $dataProduct->updateAt,
	"approveBy" => $dataProduct->approveBy,
	"approveAt" => $dataProduct->approveAt,
	"updateFrom" => $dataProduct->updateFrom
);
$response['data']['product']  = $product_arr;

// create array
$population_arr = array(
	"id" => $currentPopulationId,
	"companyId" => $dataPopulation->companyId,
	"endUserId" => $dataPopulation->endUserId,
	"productId" => $dataPopulation->productId,
	"suppliedBy" => $dataPopulation->suppliedBy,
	"dateSupplied" => $dataPopulation->dateSupplied,
	"tagNo" => $dataPopulation->tagNo,
	"service" => $dataPopulation->service,
	"factoryRef" => $dataPopulation->factoryRef,
	"salesId" => $dataPopulation->salesId,
	"dimRef" => $dataPopulation->dimRef,
	"photo1" => $finalImageUrl . $dataPopulation->photo1,
	"photo2" => $finalImageUrl . $dataPopulation->photo2,
	"photo3" => $finalImageUrl . $dataPopulation->photo3,
	"latitude" => $dataPopulation->latitude,
	"longitude" => $dataPopulation->longitude,
	"createdBy" => $dataPopulation->createdBy,
	"createdAt" => $dataPopulation->createdAt,
	"updateBy" => $dataPopulation->updateBy,
	"updateAt" => $dataPopulation->updateAt,
	"approveBy" => $dataPopulation->approveBy,
	"approveAt" => $dataPopulation->approveAt,
	"updateFrom" => $dataPopulation->updateFrom
);
$response['data']['population']  = $population_arr;
print_r(json_encode($response));

?>