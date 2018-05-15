<?php 
 
 
 //database constants
 define('DB_HOST', 'localhost');
 define('DB_USER', 'root');
 define('DB_PASS', '');
 define('DB_NAME', 'salescounter');
 
 //connecting to database and getting the connection object
 $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 
 //Checking if any error occured while connecting
 if (mysqli_connect_errno()) {
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
 die();
 }
 
 //creating a query
 $stmt = $conn->prepare("SELECT product.id, product.product_code, product.title, product.remarks, product.image, product.date_time, 
 varian_stock.id_varian, varian_stock.stock, varian.name, category.name FROM product, varian_stock, category WHERE product.id = varian_stock.id_product , varian_stock.id_varian = varian.id, varian.id_category = category.id;");
 
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
 $temp['image'] = "http://192.168.43.115/api_salescounter/image/data/" . $image; 
 $temp['date_time'] = $date_time; 
 array_push($products, $temp);
 }
 	
 //displaying the result in json format 
 echo json_encode($products);