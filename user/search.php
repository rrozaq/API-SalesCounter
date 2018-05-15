<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/user.php';

// instantiate database and user object
$database = new Database();
$db = $database->getConnection();

// initialize object
$user = new User($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query users
$stmt = $user->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// users array
	$users_arr=array();
	$users_arr["records"]=array();

	// retrieve our table contents
	// fetch() is faster than fetchAll()
	// http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$user_item=array(
			"id" => $id,
			"name" => $name,
			"email" => $email,
			"level" => $level
		);

		array_push($users_arr["records"], $user_item);
	}

	echo json_encode($users_arr);
}

else{
    echo json_encode(
		array("message" => "No users found.")
	);
}
?>