<?php

class Database {
	private static $databaseObj;
	private $connection;

	public static function getConnection() {
		if (!self::$databaseObj)
		self::$databaseObj = new self();
		return self::$databaseObj;
	}


	private function __construct() {
		try {
			$this->connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch(PDOException $exception) {
			echo "Error: " . $exception->getMessage() . "<BR>";
		}
	}

	public function sqlBindQuery($sql, $valArray = null) {
		try {
			$statement = $this->connection->prepare($sql);
			if (is_array($valArray)) {
				$statement->execute($valArray);
			} else {
				$statement->execute();
			}
			return $statement;
		} catch (PDOException $exception) {
			echo "Error: " . $exception->getMessage() . "<BR>"; 
		}
	}

	public function fetchArray($sql, $valArray = null) {
		$statement = $this->sqlBindQuery($sql, $valArray);
		if ($statement->rowCount() == 0) {
			return false;
		} else if ($statement->rowCount() >= 1) {
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	public function lastInsertId() {
		$id=$this->connection->lastInsertId();
		return $id;
	}

	public function rowCount($sql, $valArray = null) {
		$statement = $this->sqlBindQuery($sql, $valArray);
		return $statement->rowCount();
	}

}

?>