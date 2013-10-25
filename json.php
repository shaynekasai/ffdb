<?php
require_once("lib/GeoData.php");
$objGeoData = new GeoData();
echo $objGeoData->dumpJSON();
?>
