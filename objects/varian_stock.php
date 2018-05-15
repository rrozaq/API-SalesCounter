<?php
class VarianStock{

	// database connection and table name
	private $conn;
	private $table_name = "varian_stock";

	// object properties
	public $id;
	public $id_product;
	public $id_varian;
	public $stock;

	public function __construct($db){
		$this->conn = $db;
	}
	
	// create user
	function create(){
		// query to insert record			
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					id=:id, 
					id_product=:id_product,
					id_varian=:id_varian,
					stock=:stock";

		//sample data for insert
		//{"name":"John Doe"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
		$this->id_product=htmlspecialchars(strip_tags($this->id_product));
		$this->id_varian=htmlspecialchars(strip_tags($this->id_varian));
		$this->stock=htmlspecialchars(strip_tags($this->stock));
		

		// bind values
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":id_product", $this->id_product);
		$stmt->bindParam(":id_varian", $this->id_varian);
		$stmt->bindParam(":stock", $this->stock);
		

		// execute query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// used by select drop-down list
	public function read(){

		//select all data
		$query = "SELECT
					id, id_product, id_varian, stock
				FROM
					" . $this->table_name . "
				ORDER BY
					id";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
	
	// delete the product
	function delete(){
		// delete query
		$query = "DELETE 
				FROM 
					" . $this->table_name . " 
				WHERE 
					id = ?";
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
	
	// update the product
	function update(){

		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET 
					id_product=:id_product,
					 id_varian=:id_varian, 
					 stock=:stock
				WHERE
					id = :id";
		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","category_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id_product=htmlspecialchars(strip_tags($this->id_product));
		$this->id_varian=htmlspecialchars(strip_tags($this->id_varian));	
		$this->stock=htmlspecialchars(strip_tags($this->stock));			
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(":id_product", $this->id_product);
		$stmt->bindParam(":id_varian", $this->id_varian);
		$stmt->bindParam(":stock", $this->stock);		
		$stmt->bindParam(":id", $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
}
?>