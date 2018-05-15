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
include_once '../objects/order.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$order = new Order($db);

$data = json_decode(file_get_contents("php://input"));

$order->sales_id = $data->sales_id;
$order->tgl_awal = $data->tgl_awal;
$order->tgl_akhir = $data->tgl_akhir;


// query product
$stmt = $order->report($order->sales_id, $order->tgl_awal, $order->tgl_akhir);
$num = $stmt->rowCount();


// check if more than 0 record found
if($num>0){

	// order_detail array
	$report_arr=array();
	// $laporan_arr["records"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$detail_item=array(
			"id" => $id,
			"sales_id" => $sales_id,
			"date_time" => $date_time,
			"keterangan" => $keterangan,
            "tujuan" => $tujuan,
            "orderDetailId" => $orderdetailId,
            "order_id" => $order_id,
            "product_code" => $product_code,
            "id_varian" => $id_varian,
            "qty" => $qty,
            "productId" => $productId,
            "title" => $title,
            "remarks" => $remarks,
            "varianId" => $varianId,
            "name" => $name,
            

		);

		array_push($report_arr, $detail_item);
	}

	echo json_encode($report_arr);
}

else{
    echo json_encode(
		array("message" => "No report found.")
	);
}
?>