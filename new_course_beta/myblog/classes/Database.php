<?php 
class Database{
	private $host = 'localhost';
	private $user = 'youssef';
	private $pass = '123';
	private $dbname = 'my_blog';

	private $dbh;
	private $error;
	private $stmt;

	public function __construct(){
		// Set DSN
		$dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
		//Set Options
		$Options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
		);
		//Create new PDO
		try{
			$this->dbh = new PDO($dsn,$this->user,$this->pass,$Options);
		}catch(PDOEception $e){
			$this->error = $e->getMessage();
		}
	}

	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}

	public function bind($param,$value,$type=null){
		if(is_null($type)){
			switch (true) {
				case is_int($value):
					# code...
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					# code...
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					# code...
					$type = PDO::PARAM_NULL;
					break;
				
				default:
					# code...
					$type = PDO::PARAM_STR;
					break;
			}

		}
		$this->stmt->bindValue($param,$value,$type);
	}

	public function execute(){
		return $this->stmt->execute();
	}

	public function lastInsertId(){
		$this->dbh->lastInsertId();
	}

	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>