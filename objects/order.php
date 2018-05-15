<?php
class Order{

	// database connection and table name
	private $conn;
	private $table_name = "orders";

	// object properties
	public $id;
	public $sales_id;
	public $product_code;
	public $id_varian;
	public $qty;
	public $date_time;	
	public $keterangan;
	public $tujuan;
	public $tgl_awal;
	public $tgl_akhir;

	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}
	
	// read products
	function read(){

		// select all query
		$query = "SELECT
					id,
					sales_id,
					product_code,
					id_varian,
					qty,
					date_time
				FROM
					" . $this->table_name . "
				ORDER BY
					id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	function create(){
		// query to insert record			
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					sales_id=:sales_id,
					date_time=:date_time,
					keterangan=:keterangan,
					tujuan=:tujuan";

		//sample data for insert
		//{"name":"John Doe","email":"a@a.com","password":"12345"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		
		$this->sales_id=htmlspecialchars(strip_tags($this->sales_id));
		$this->date_time=htmlspecialchars(strip_tags($this->date_time));
		$this->keterangan=htmlspecialchars(strip_tags($this->keterangan));
		$this->tujuan=htmlspecialchars(strip_tags($this->tujuan));
		
		
		// bind values
		
		$stmt->bindParam(":sales_id", $this->sales_id);
		$stmt->bindParam(":date_time", $this->date_time);
		$stmt->bindParam(":keterangan", $this->keterangan);
		$stmt->bindParam(":tujuan", $this->tujuan);
		


		// execute query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	

	function report($sales_id, $tgl_awal, $tgl_akhir){
		
				// select all query
					$query = "SELECT
                    a.id,
                    a.sales_id,
                    DATE_FORMAT(STR_TO_DATE(a.date_time, '%d/%m/%Y'),'%d/%m/%Y') as date_time,
                    a.keterangan,
                    a.tujuan,
                    b.id as orderdetailId,
                    b.order_id,
                    b.product_code,
                    b.id_varian,
                    b.qty,
                    c.id as productId,
                    c.product_code,
                    c.title,
                    c.remarks,
                    d.id as varianId,
                    d.id_category,
                    d.name
 
                FROM
                    orders a
                INNER JOIN
                    order_detail b
                    ON b.order_id = a.id
                INNER JOIN
                    product c
                    ON c.product_code = b.product_code
                INNER JOIN
                    varian d
                    ON
                    d.id = b.id_varian
                WHERE 
                	a.sales_id = ?
                    AND
                STR_TO_DATE(a.date_time, '%d/%m/%Y') 
                
                >= STR_TO_DATE(?, '%d/%m/%Y') AND STR_TO_DATE(a.date_time, '%d/%m/%Y') 
                
                <= STR_TO_DATE(?, '%d/%m/%Y')";
		
				// prepare query statement
				$stmt = $this->conn->prepare($query);
				

				$sales_id=htmlspecialchars(strip_tags($sales_id));						
				$tgl_awal=htmlspecialchars(strip_tags($tgl_awal));
				$tgl_akhir=htmlspecialchars(strip_tags($tgl_akhir));

				//
				$sales_id = "{$sales_id}";
				$tgl_awal = "{$tgl_awal}";
				$tgl_akhir = "{$tgl_akhir}";

				$stmt->bindParam(1, $sales_id);
				$stmt->bindParam(2, $tgl_awal);
				$stmt->bindParam(3, $tgl_akhir);
				
				$stmt->execute();

				return $stmt;

				// prepare query statement

			}

			
	
	// used when filling up the update product form
	function readOne(){

		// query to read single record
		$query = "SELECT
					id,
					sales_id,
					product_code,
					id_varian,
					qty,
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
		$this->sales_id = $row['sales_id'];
		$this->product_code = $row['product_code'];
		$this->id_varian = $row['id_varian'];
		$this->qty = $row['qty'];		
		$this->date_time = $row['date_time'];
	}
	
	// update the product
	function update(){

		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					sales_id = :sales_id,
					product_code = :product_code,
					id_varian = :id_varian,
					qty = :qty,
					date_time = :date_time
				WHERE
					id = :id";

		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","category_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->sales_id=htmlspecialchars(strip_tags($this->sales_id));
		$this->product_code=htmlspecialchars(strip_tags($this->product_code));
		$this->id_varian=htmlspecialchars(strip_tags($this->id_varian));
		$this->qty=htmlspecialchars(strip_tags($this->qty));		
		$this->date_time=htmlspecialchars(strip_tags($this->date_time));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind new values
		$stmt->bindParam(':sales_id', $this->sales_id);
		$stmt->bindParam(':product_code', $this->product_code);
		$stmt->bindParam(':id_varian', $this->id_varian);
		$stmt->bindParam(':qty', $this->qty);		
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
	
	// search products
	function search($keywords){

		// select all query
		$query = "SELECT
					id,
					sales_id,
					product_code,
					id_varian,
					qty,
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
	public function readPaging($from_record_num, $records_per_page){

		// select query
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.image, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
				ORDER BY p.created DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind variable values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}
	
	// used for paging products
	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}
	
	public function getMIMETYPE($base64string){
		preg_match("/^data:image\/(.*);base64/i",$base64string, $match);
		return $match[1];
	}
	
	function getImageMimeType($imagedata){
		$imagemimetypes = array( 
			"jpeg" => "FFD8", 
			"png" => "89504E470D0A1A0A", 
			"gif" => "474946",
			"bmp" => "424D", 
			"tiff" => "4949",
			"tiff" => "4D4D"
		);

		foreach ($imagemimetypes as $mime => $hexbytes){
			$bytes = getBytesFromHexString($hexbytes);
			if (substr($imagedata, 0, strlen($bytes)) == $bytes)
			return $mime;
		}

		return NULL;
	}
	
	// used when filling up the update product form
	function deleteImage(){

		// query to read single record
		$query = "SELECT
					image
				FROM
					" . $this->table_name . "
				WHERE
					id = ?
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
		$this->image = $row['image'];
	}
}