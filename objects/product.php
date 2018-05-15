<?php
class Product{

	// database connection and table name
	private $conn;
	private $table_name = "product";

	// object properties
	public $id;
	public $product_code;
	public $title;
	public $remarks;
	public $id_varian;
	public $stock;
	public $image;
	public $date_time;


	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	
	// read products
	function read(){

		// select all query
		$query = "SELECT
					id,
					product_code,
					title,
					remarks,
					id_varian,
					stock,
					image,
					date_time
				FROM
					" . $this->table_name . "
				ORDER BY............
					id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	function laporan($id_category){
		
				// select all query
				$query = "SELECT
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
							d.id as categoryId,
							d.name as categoryName

						FROM
							product a
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
							a.id LIMIT 0,1";
		
				// prepare query statement
				$stmt = $this->conn->prepare($query);

				$id_category=htmlspecialchars(strip_tags($id_category));
				$id_category = "{$id_category}";
		
				// bind
				$stmt->bindParam(1, $id_category);
		
		
				// execute query
				$stmt->execute();
		
				return $stmt;
			}


			function stock($product_code, $id_varian){
				
						// select all query
						$query = "SELECT
							a.id,
							a.product_code,
							a.title,
							a.remarks,
							a.image,
							a.date_time,
							b.id as varianStockId,
							b.id_product,
							b.id_varian,
							b.stock,
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
							a.product_code = ? 
							AND 
							b.id_varian = ?
						ORDER BY
							a.id";
				
						// prepare query statement
						$stmt = $this->conn->prepare($query);
		
						$product_code=htmlspecialchars(strip_tags($product_code));						
						$id_varian=htmlspecialchars(strip_tags($id_varian));

						$product_code = "{$product_code}";						
						$id_varian = "{$id_varian}";
				
						// bind
						$stmt->bindParam(1, $product_code);
						$stmt->bindParam(2, $id_varian);
						
				
				
						// execute query
						$stmt->execute();
				
						return $stmt;
					
							}

							function varstock($id){
								
										// select all query
										$query = "SELECT
											a.id,
											a.product_code,
											a.title,
											a.remarks,
											a.image,
											a.date_time,
											b.id as varianStockId,
											b.id_product,
											b.id_varian,
											b.stock,
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
											a.id = ? 
										ORDER BY
											a.id";
								
										// prepare query statement
										$stmt = $this->conn->prepare($query);
						
										$id=htmlspecialchars(strip_tags($id));						
				
										$id = "{$id}";						
								
										// bind
										$stmt->bindParam(1, $id);
										
								
								
										// execute query
										$stmt->execute();
								
										return $stmt;
									
											}


							function varian($product_code){
								
										// select all query
										$query = "SELECT
							a.id,
							a.product_code,
							a.title,
							a.remarks,
							a.image,
							a.date_time,
							b.id as varianStockId,
							b.id_product,
							b.id_varian,
							b.stock,
							c.id as varianId,
							c.id_category,
							c.name,
							d.id,
							d.name as categoryName

						FROM
							product a
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
							a.product_code = ?
						ORDER BY
							a.id";
								
										// prepare query statement
										$stmt = $this->conn->prepare($query);
						
										$product_code=htmlspecialchars(strip_tags($product_code));						
				
										$product_code = "{$product_code}";						
								
										// bind
										$stmt->bindParam(1, $product_code);										
								
								
										// execute query
										$stmt->execute();
								
										return $stmt;
									
											}
	
	// create product
	function create(){
		// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					product_code=:product_code,
					title=:title,
					remarks=:remarks,
					image=:image,
					date_time=:date_time";

		//sample data for insert
		//{"name":"John Doe","price":"2000","description":"sssss","category_id":"1","created":"0"}
		// prepare query
		$stmt = $this->conn->prepare($query);
		
		// sanitize
		$this->product_code=htmlspecialchars(strip_tags($this->product_code));
		$this->title=htmlspecialchars(strip_tags($this->title));
		$this->remarks=htmlspecialchars(strip_tags($this->remarks));		
		$this->image=htmlspecialchars(strip_tags($this->image));
		$this->date_time=htmlspecialchars(strip_tags($this->date_time));

		// bind values
		$stmt->bindParam(":product_code", $this->product_code);
		$stmt->bindParam(":title", $this->title);
		$stmt->bindParam(":remarks", $this->remarks);	
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":date_time", $this->date_time);
		

		// execute query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	
	
	// used when filling up the update product form
	function readOne(){

		// query to read single record
		$query = "SELECT
					id,
					product_code,
					title,
					remarks,
					id_varian,
					stock,
					image,
					date_time
				FROM
					" . $this->table_name . "
				WHERE
					product_code = ?
				LIMIT
					0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id of product to be updated
		$stmt->bindParam(1, $this->product_code);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties
		$this->id = $row['id'];
		$this->product_code = $row['product_code'];
		$this->title = $row['title'];
		$this->remarks = $row['remarks'];
		$this->id_varian = $row['id_varian'];
		$this->stock = $row['stock'];		
		$this->image = $row['image'];
		$this->date_time = $row['date_time'];
	}
	
	// update the product
	function update(){
		
				// update query
				$query = "UPDATE
							" . $this->table_name . "
						SET
							product_code = :product_code,					
							title = :title,
							remarks = :remarks,
							image = :image,
							date_time = :date_time
						WHERE
							id = :id";
		
				//object for update
				//{"title":"John Doe","price":"2000","value":"sssss","category_id":"1","id":"1"}
				// prepare query statement
				$stmt = $this->conn->prepare($query);
		
				// sanitize
				$this->product_code=htmlspecialchars(strip_tags($this->product_code));
				$this->title=htmlspecialchars(strip_tags($this->title));
				$this->remarks=htmlspecialchars(strip_tags($this->remarks));
				$this->image=htmlspecialchars(strip_tags($this->image));
				$this->date_time=htmlspecialchars(strip_tags($this->date_time));				
				$this->id=htmlspecialchars(strip_tags($this->id));
		
				// bind new values
				$stmt->bindParam(':product_code', $this->product_code);
				$stmt->bindParam(':title', $this->title);
				$stmt->bindParam(':remarks', $this->remarks);
				$stmt->bindParam(':image', $this->image);
				$stmt->bindParam(':date_time', $this->date_time);				
				$stmt->bindParam(':id', $this->id);
		
				// execute the query
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}
			}
			
			// delete the product
			function delete(){
		
				// delete query
				$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		
				//object for delete
				//{"id":"1"}
				// prepare query
				$stmt = $this->conn->prepare($query);
		
				// sanitize
				$this->id=htmlspecialchars(strip_tags($this->id));
		
				// bind id of record to delete
				$stmt->bindParam(1, $this->id);
		
				// execute query
				if($stmt->execute()){
					return true;
				}
		
				return false;
				
			}
		}

	// delete the product
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		//object for delete
		//{"id":"1"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
		
	}
	
	// search products
	function search($keywords){

		// select all query
		$query = "SELECT
					id,
					product_code,
					title,
					remarks,
					id_varian,
					stock,
					image,
					date_time
				FROM
					" . $this->table_name . "
				WHERE
					product_code = ?
				ORDER BY
					id DESC";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "{$keywords}";

		// bind
		$stmt->bindParam(1, $keywords);

		// execute query
		$stmt->execute();

		return $stmt;
	}
	// read products with pagination
	// public function readPaging($from_record_num, $records_per_page){

	// 	// select query
	// 	$query = "SELECT
	// 				c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.image, p.created
	// 			FROM
	// 				" . $this->table_name . " p
	// 				LEFT JOIN
	// 					categories c
	// 						ON p.category_id = c.id
	// 			ORDER BY p.created DESC
	// 			LIMIT ?, ?";

	// 	// prepare query statement
	// 	$stmt = $this->conn->prepare( $query );

	// 	// bind variable values
	// 	$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
	// 	$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

	// 	// execute query
	// 	$stmt->execute();

	// 	// return values from database
	// 	return $stmt;
	// }
	
	// // used for paging products
	// public function count(){
	// 	$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

	// 	$stmt = $this->conn->prepare( $query );
	// 	$stmt->execute();
	// 	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 	return $row['total_rows'];
	// }
	
	// public function getMIMETYPE($base64string){
	// 	preg_match("/^data:image\/(.*);base64/i",$base64string, $match);
	// 	return $match[1];
	// }
	
	// function getImageMimeType($imagedata){
	// 	$imagemimetypes = array( 
	// 		"jpeg" => "FFD8", 
	// 		"png" => "89504E470D0A1A0A", 
	// 		"gif" => "474946",
	// 		"bmp" => "424D", 
	// 		"tiff" => "4949",
	// 		"tiff" => "4D4D"
	// 	);

	// 	foreach ($imagemimetypes as $mime => $hexbytes){
	// 		$bytes = getBytesFromHexString($hexbytes);
	// 		if (substr($imagedata, 0, strlen($bytes)) == $bytes)
	// 		return $mime;
	// 	}

	// 	return NULL;
	// }
	
	// // used when filling up the update product form
	// function deleteImage(){

	// 	// query to read single record
	// 	$query = "SELECT
	// 				image
	// 			FROM
	// 				" . $this->table_name . "
	// 			WHERE
	// 				id = ?
	// 			LIMIT
	// 				0,1";

	// 	// prepare query statement
	// 	$stmt = $this->conn->prepare( $query );

	// 	// bind id of product to be updated
	// 	$stmt->bindParam(1, $this->product_code);

	// 	// execute query
	// 	$stmt->execute();

	// 	// get retrieved row
	// 	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	// 	// set values to object properties
	// 	$this->id = $row['id'];
	// 	$this->image = $row['image'];
	// }