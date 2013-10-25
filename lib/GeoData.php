<?php


require_once("lib/DB.php");


const PAGE_SIZE = 256;

class GeoData {
	private $objDB;


	function __construct() {
		$this->objDB = new DB();
		$this->objDB->setDatabase("ffdb");
	}
	

	public function dumpJSON() {
		/* get all records */
		$cursor = $this->objDB->findAll("ff_geo_points", array("_id" => false, "id" => true, "geo_desc" => true, "point" => true));
		$pointer = 0;
		$arrProcesses = array();
		$arrProcess = array();
		
	
		
		foreach ($cursor as $doc) {
			array_push($arrProcess, $doc);
			    
		    if($pointer >= PAGE_SIZE - 1) { // or if end of 
		    	// push into $arrProcesses
		    	array_push($arrProcesses, array("process" => $arrProcess));
		    	$arrProcess = array();
				$pointer = 0;    
		    } else {
		    	$pointer++;
		    }
		}
		array_push($arrProcesses, array("process" => $arrProcess));
		
		return json_encode(array("dataset" => $arrProcesses));    	
		
	}
}

?>