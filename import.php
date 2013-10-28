<?php

/**
 * TODO: 
 * - need to turn this into a class... this is really quick and dirty, sorry!
 * - thinking about taking out subdoc as well in mongo and using the obj id instead... since the govt provides it in two files anyways
 */
require_once("lib/DB.php");

$objDB = new DB();
$objDB->setDatabase("ffdb");

const WEATHER_DATA_FOLDER = "";
const FIRE_DATA    = "data/BCGW_78757263_13824546384_8968/C_FIRE_PNT/C_FIRE_PNT.csv";
const FIRE_STATIONS       = "data/crmp_network_geoserver.csv";

// this is returned as a nested document
function getRawWeatherData($climateId) {
	$windBundle = array();
	echo 'Loading... ' . $climateId . ".csv<br/>";
			
	if (($file_handle = fopen("data/pcds_data/EC_RAW/{$climateId}.csv", "r")) !== FALSE) {	
		$read_header = false;
		
		$idx_wind_speed = null;
		$idx_wind_direction = null;
		$idx_time = null;
				
				
		while (($data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
			$wind_speed = 0;
			$wind_direction = 0;
			$wind_time = '';
			
			if($read_header == true) {
			
				if($idx_wind_speed &&  array_key_exists($idx_wind_speed, $data)) {
					$wind_speed =  $data[$idx_wind_speed];
				}
				
				if($idx_wind_direction &&  array_key_exists($idx_wind_direction, $data)) {
					$wind_direction =  $data[$idx_wind_direction];
				}
				
				if($idx_time &&  array_key_exists($idx_time, $data)) {
					$wind_time =  $data[$idx_time];
				}
			
				if ($wind_direction != 'NaN' && floatval($wind_direction) > 0) {
					$wind_data = array("climateId" => $climateId, "wind" => array("speed" => $wind_speed, "direction" => $wind_direction), "datestamp" => new MongoDate(strtotime($wind_time)));
					array_push($windBundle, $wind_data);
				}
			}
			
			if (substr($data[0], 0, strlen('station_observations')) === 'station_observations'   ) {
			    var_dump($data);
			    
			    $idx_wind_speed     = array_search('station_observations.wind_speed', $data);
			    $idx_wind_direction = array_search('station_observations.wind_direction', $data);
			    $idx_time           = array_search('station_observations.time', $data);
				$read_header = true;
			}					
			
		}
		fclose($file_handle);
	
	    
	    	
	}
	return($windBundle);
}



/* brute force, aint meant to be pretty or efficient */
if( $_GET['action'] == 'import-weather') {
	// import weather
	
	if (($stations_handle = fopen(FIRE_STATIONS, "r")) !== FALSE) {
		$bundle = array();
		
		while (($data = fgetcsv($stations_handle, 1000, ",")) !== FALSE) {
			$station = $data[2];
		 	$lat = $data[5];
		 	$lng = $data[4];
		 	$station_name = $data[3];
		 	$station_id = $data[11];
		 	
		 	if(is_numeric($station_id)) {
			 	// insert into database
			 	$arrInsert = array("_id" => $station_id, "climateId" => $station, "point" => array("lat" => $lat, "lng" => $lng), "station_name" => $station_name, "wind_data" => getRawWeatherData($station) );
			 	
			 	array_push($bundle, $arrInsert );

		 	} else {
			 	echo "skipping..." . $station_id . "<br/>";
		 	}
		 	
		}
		fclose($stations_handle);
		
		
		$objDB->batchInsert("ff_station_data", $bundle, true);
		
	}
	
	

	    
	    // write to DB
	    //$objDB->batchInsert("ff_station_data", $windBundle, true);
	    
	
	
} else if ($_GET['action'] == 'import-fire-data') {
	// import fire data
	if (($handle = fopen(FIRE_DATA, "r")) !== FALSE) {
		$bundle = array();
		
		
		$row = 0;
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	    	if($row == 0) {
	    		$row++;
		    	continue;
	    	}
	        
	        $arrInsert = array("id" => $data[0], "geo_desc" => $data[13], "fire_cause" => $data[3], "point" => array("lat" => $data[7], "lng" => $data[1])  );
	        array_push($bundle, $arrInsert );
	        echo var_dump($arrInsert);
	        $row++;
	    }
	    fclose($handle);
	}
	
	$objDB->batchInsert("ff_geo_points", $bundle, true);
}

?>
