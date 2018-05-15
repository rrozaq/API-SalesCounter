<?php
class Varian{

	// database connection and table name
	private $conn;
	private $table_name = "varian";

	// object properties
	public $id;
	public $id_category;
	public $name;

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
					id_category=:id_category, 
					name=:name";

		//sample data for insert
		//{"name":"John Doe","email":"a@a.com","password":"12345"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
		$this->id_category=htmlspecialchars(strip_tags($this->id_category));
		$this->name=htmlspecialchars(strip_tags($this->name));

		// bind values
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":id_category", $this->id_category);
		$stmt->bindParam(":name", $this->name);
		
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
					id, id_category, name
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
					name=:name,
					id_category=:id_category
				WHERE
					id = :id";
		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","varian_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->id_category=htmlspecialchars(strip_tags($this->id_category));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":id_category", $this->id_category);
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