<?php 
 
 
 //database constants
 define('DB_HOST', 'localhost');
 define('DB_USER', 'root');
 define('DB_PASS', '');
 define('DB_NAME', 'salescounter');
 
 include_once '../config/core.php';
 
 //connecting to database and getting the connection object
 $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 
 //Checking if any error occured while connecting
 if (mysqli_connect_errno()) {
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
 die();
 }
 
 //creating a query
 $stmt = $conn->prepare("SELECT id, product_code, title, remarks, image, date_time FROM product;");
 
 //executing the query 
 $stmt->execute();
 
 //binding results to the query 
 $stmt->bind_result($id, $product_code, $title, $remarks, $image, $date_time);
 
 $products = array(); 
 
 //traversing through all the result 
 while($stmt->fetch()){
 $temp = array();
 $temp['id'] = $id; 
 $temp['product_code'] = $product_code; 
 $temp['title'] = $title; 
 $temp['remarks'] = $remarks; 
 $temp['image'] = $data_image_url . $image; 
 $temp['date_time'] = $date_time; 
 array_push($products, $temp);
 }
 	
 //displaying the result in json format 
 echo json_encode($products);