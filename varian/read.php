<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/varian.php';

// instantiate database and varian object
$database = new Database();
$db = $database->getConnection();

// initialize object
$varian = new Varian($db);

// query varians
$stmt = $varian->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// products array
	$categories_arr=array();
	$categories_arr["records"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$varian_item=array(
			"id" => $id,
			"name" => $name
		);

		array_push($categories_arr["records"], $varian_item);
	}

	echo json_encode($categories_arr);
}

else{
    echo json_encode(
		array("message" => "No products found.")
	);
}
?>