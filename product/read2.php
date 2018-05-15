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
 $stmt = $conn->prepare("
 SELECT
 a.id,
 a.product_code,
 a.title,
 a.remarks,
 a.image,
 a.date_time,
 b.id as varianStockId,
 b.id_product as varianStockIdProduct,
 b.id_varian,
 b.stock as varianStock,
 c.id as varianId,
 c.id_category,
 c.name as varianName,
 d.id,
 d.name as categoryName

FROM
 " . $this->table_name . " a
RIGHT JOIN 
 varian_stock b
 ON b.id_product = a.id
LEFT JOIN 
 varian c
 ON c.id = b.id_varian
LEFT JOIN 
 category d
 ON d.id = c.id_category
WHERE 
c.id_category = ?
ORDER BY
 a.id");
 
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