<?php
class OrderDetail{

	// database connection and table name
	private $conn;
	private $table_name = "order_detail";

	// object properties
	public $id;
	public $order_id;
	public $product_code;
	public $id_varian;
	public $qty;

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
					order_id=:order_id, 
					product_code=:product_code, 
					id_varian=:id_varian,
					qty=:qty";

		//sample data for insert
		//{"name":"John Doe","email":"a@a.com","password":"12345"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
		$this->order_id=htmlspecialchars(strip_tags($this->order_id));
		$this->product_code=htmlspecialchars(strip_tags($this->product_code));
		$this->id_varian=htmlspecialchars(strip_tags($this->id_varian));		
		$this->qty=htmlspecialchars(strip_tags($this->qty));

		// bind values
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":order_id", $this->order_id);
		$stmt->bindParam(":product_code", $this->product_code);
		$stmt->bindParam(":id_varian", $this->id_varian);		
		$stmt->bindParam(":qty", $this->qty);
		
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
					id, 
					order_id,
					product_code,
					id_varian,
					qty
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
					order_id=:order_id, 
					product_code=:product_code,
					id_varian=:id_varian,
					qty=:qty
				WHERE
					id = :id";
		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","order_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->order_id=htmlspecialchars(strip_tags($this->order_id));
		$this->product_code=htmlspecialchars(strip_tags($this->product_code));
		$this->id_varian=htmlspecialchars(strip_tags($this->id_varian));
		$this->qty=htmlspecialchars(strip_tags($this->qty));		
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(":order_id", $this->order_id);
		$stmt->bindParam(":product_code", $this->product_code);
		$stmt->bindParam(":id_varian", $this->id_varian);
		$stmt->bindParam(":qty", $this->qty);		
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