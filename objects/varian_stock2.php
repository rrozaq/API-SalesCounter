<?php
class VarianStock{

	// database connection and table id_varian
	private $conn;
	private $table_name = "varian_stock";

	// object properties
	public $id;
	public $id_product;
	public $id_varian;
	public $stock;
	public $id_category;
	public $name;

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

		// //select all data
		// $query = "SELECT
		// 			a.id, 
		// 			a.id_product,
		// 			a.id_varian,
		// 			a.stock
		// 		FROM
		// 			" . $this->table_name . " a,
		// 			varian b, product c WHERE 
		// 			a.id_varian = b.id AND a.id_product = c.product_code
		// 		ORDER BY
		// 			a.id";			
		
		//select all data
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
							c.name as name,
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
					    	c.name = 'Motif kartun'
						ORDER BY
							a.id";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
}
?>