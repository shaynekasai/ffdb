<?php

class DB {
	public $dbh;
	public $db;
	
	function __construct() {
		$this->dbh = new MongoClient();		
	}
	
	public function setDatabase($db) {
		$this->db = $this->dbh->$db;
		
	}
	public function batchInsert($collection, $data, $drop = false) {
		if($drop == true) {	
			$this->db->$collection->drop();	
		}
	
		$this->db->$collection->batchInsert($data);
	}
	/*
	 $collection string - the collection name
	 $projection array - key value  
	 */
	public function findAll($collection, $projection = "", $query = array()) {
		return $this->db->$collection->find($query,$projection);
	}
	
	
}
?>