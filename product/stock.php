<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../config/core.php';
include_once '../objects/product.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

$product->product_code = $data->product_code;
$product->id_varian = $data->id_varian;

// query product
$stmt = $product->stock($product->product_code, $product->id_varian);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// order_detail array
	$laporan_arr=array();
	// $laporan_arr["records"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$order_detail_item=array(
			"id" => $id,
			"product_code" => $product_code,
			"title" => $title,
			"remarks" => $remarks,
            "image" => $data_image_url . $image,
            "date_time" => $date_time,
            "varianStockId" => $varianStockId,
            "id_product" => $id_product,
            "id_varian" => $id_varian,
            "stock" => $stock,
            "varianId" => $varianId,
            "id_category" => $id_category,
            "varianName" => $varianName,
            "categoryName" => $categoryName

		);

		array_push($laporan_arr, $order_detail_item);
	}

	echo json_encode($laporan_arr);
}

else{
    echo json_encode(
		array("message" => "No order_detail found.")
	);
}
?>