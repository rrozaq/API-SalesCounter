<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/order_detail.php';

// instantiate database and order_detail object
$database = new Database();
$db = $database->getConnection();

// initialize object
$order_detail = new OrderDetail($db);

// query order_details
$stmt = $order_detail->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// order_detail array
	$order_detail_detail_arr=array();
	$order_detail_detail_arr["records"]=array();

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
			"order_id" => $order_id,
			"product_code" => $product_code,
			"qty" => $qty,
			"date_time" => $date_time
		);

		array_push($order_detail_detail_arr["records"], $order_detail_item);
	}

	echo json_encode($order_detail_detail_arr);
}

else{
    echo json_encode(
		array("message" => "No order_detail found.")
	);
}
?>