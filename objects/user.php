<?php
class User{

	// database connection and table name
	private $conn;
	private $table_name = "user";

	// object properties
	public $id;
	public $unique_id;
	public $name;
	public $email;
	public $encrypted_password;
	public $password;
	public $salt;
	public $token;
	public $level;

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
					unique_id=:unique_id, 
					name=:name, 
					email=:email,
					encrypted_password=:encrypted_password, 
					salt=:salt,
					token=:token,
					level=:level";

		//sample data for insert
		//{"name":"John Doe","email":"a@a.com","password":"12345"}
		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));
		$this->unique_id=htmlspecialchars(strip_tags($this->unique_id));
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->encrypted_password=htmlspecialchars(strip_tags($this->encrypted_password));
		$this->salt=htmlspecialchars(strip_tags($this->salt));
		$this->token=htmlspecialchars(strip_tags($this->token));
		$this->level=htmlspecialchars(strip_tags($this->level));

		// bind values
		$stmt->bindParam(":id", $this->id);
		$stmt->bindParam(":unique_id", $this->unique_id);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":encrypted_password", $this->encrypted_password);
		$stmt->bindParam(":salt", $this->salt);
		$stmt->bindParam(":token", $this->token);
		$stmt->bindParam(":level", $this->level);

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
					id, name, email, token, level
				FROM
					" . $this->table_name . "
				ORDER BY
					id";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
	
	public function readSales(){
		
				//select all data
				$query = "SELECT
							id, name, email, token, level
						FROM
							" . $this->table_name . " WHERE level = 'Salesman'
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
	function updateAllData(){

		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					unique_id=:unique_id, 
					name=:name, 
					email=:email,
					encrypted_password=:encrypted_password, 
					salt=:salt, 
					level=:level
				WHERE
					id = :id";
		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","category_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->unique_id=htmlspecialchars(strip_tags($this->unique_id));
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->encrypted_password=htmlspecialchars(strip_tags($this->encrypted_password));
		$this->salt=htmlspecialchars(strip_tags($this->salt));
		$this->level=htmlspecialchars(strip_tags($this->level));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(":unique_id", $this->unique_id);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":encrypted_password", $this->encrypted_password);
		$stmt->bindParam(":salt", $this->salt);
		$stmt->bindParam(":level", $this->level);
		$stmt->bindParam(":id", $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	// update the product
	function updateSomeData(){

		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET 
					name=:name, 
					email=:email, 
					level=:level
				WHERE
					id = :id";
		//object for update
		//{"name":"John Doe","price":"2000","description":"sssss","category_id":"1","id":"1"}
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->email=htmlspecialchars(strip_tags($this->email));
		$this->level=htmlspecialchars(strip_tags($this->level));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":level", $this->level);
		$stmt->bindParam(":id", $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	// used when filling up the update user form
	function login(){
	//param : http://localhost/api_test/user/read_one.php?email=a@a.com
		// query to read single record
		$query = "SELECT
					*
				FROM
					" . $this->table_name . "
				WHERE
					email = ? AND encrypted_password = ?
				LIMIT
					0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );
		
		// bind id of user to be updated
		//$salt = "a7cd431b6e";
		//$salt =  salt;
        $password = $this->checkhashSSHA($this->getSalt($this->email),  $this->password);
		
		$stmt->bindParam(1, $this->email);
		$stmt->bindParam(2, $password);
		
		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);	
		
		// set values to object properties
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->level = $row['level'];
		//$this->encrypted_password = $row['encrypted_password'];
		//$this->salt = $row['salt'];
		//$this->password = $password;
	}
	
	// search products
	function search($keywords){

		// select all query
		$query = "SELECT
					id, name, email, level
				FROM
					" . $this->table_name . "
				WHERE
					name LIKE ? OR email LIKE ? OR level LIKE ?
				ORDER BY
					id ASC";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);

		// execute query
		$stmt->execute();

		return $stmt;
	}
	
	/**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
	
	public function getSalt($email) {
        $query = "SELECT
					salt
				FROM
					" . $this->table_name . "
				WHERE
					email = ?
				LIMIT
					0,1";
		// prepare query statement
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->email);
		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);	
        return $row['salt'];
    }
}
?>