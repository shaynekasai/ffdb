<?php
require_once("lib/DB.php");

class StationData {
	private $objDB;

	function __construct() {
		$this->objDB = new DB(); // temporary for now
		$this->objDB->setDatabase("ffdb");
	}
	
	
	
	public function dumpJSON() {
		// get climateId, lat, lng -> wind data
		$arrProcesses = array();
		
		$query = array(
						"wind_data.datestamp" => new MongoDate(strtotime("2013-10-24")),
						"wind_data.wind.direction" => array('$ne' => "NaN")
					  );
		
		$cursor = $this->objDB->findAll("ff_station_data",array("wind_data" => true, "_id" => false, "climate_id" => true, "point" => true), $query);
		foreach ($cursor as $doc) {
			array_push($arrProcesses, $doc);
		}
		return json_encode(array("dataset" => $arrProcesses));    	
	}
}
?>
