<?php
// TODO, extend these from a JSON class

require_once("lib/GeoData.php");
require_once("lib/StationData.php");

if($_GET['dataset'] == 'frequency') {
	$objGeoData = new GeoData();
	echo $objGeoData->dumpJSON();
} else if ($_GET['dataset'] == 'stations') {
	$objStationData = new StationData();
	echo $objStationData->dumpJSON();
}
?>
