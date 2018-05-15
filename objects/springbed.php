<?php
class Springbed{

	// database connection and table id_varian
	private $conn;
	private $table_name = "product";

	// object properties
	public $id;
	public $product_code;
	public $title;
	public $remarks;
	public $image;
	public $date_time;

	public function __construct($db){
		$this->conn = $db;
	}
	
	// create user
	function create(){
		// query to insert record			
		$query = "INSERT INTO
					" . $this->table_id_varian . "
				SET
					id=:id, 
					id_product=:id_product, 
					id_varian=:id_varian, 
					stock=:stock";

		//sample data for insert
		//{"id_varian":"John Doe","email":"a@a.com","password":"12345"}
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
					a.id, 
					a.product_code,
					a.title,
					a.remarks, 
                    a.image,
                    a.date_time,
                    b.id_varian,
                    c.id_category
                    d.name
				FROM
					" . $this->table_name . " a,
					varian_stock b, varian c, category d WHERE 
					d.id_category = '4'
				ORDER BY
					a.id";			

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
}
?>