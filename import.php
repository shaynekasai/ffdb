<?php
require_once("lib/DB.php");

$objDB = new DB();
$objDB->setDatabase("ffdb");



/* brute force, aint meant to be pretty or efficient */


if (($handle = fopen("BCGW_78757263_13824546384_8968/C_FIRE_PNT/C_FIRE_PNT.csv", "r")) !== FALSE) {
	$bundle = array();
	
	
	$row = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	if($row == 0) {
    		$row++;
	    	continue;
    	}
        #$num = count($data);
        #echo "<p> $num fields in line $row: <br /></p>\n";
        #$row++;
        
        #for ($c=0; $c < $num; $c++) {
        #    echo $data[$c] . "<br />\n";
        #}
        
        $arrInsert = array("id" => $data[0], "geo_desc" => $data[13], "fire_cause" => $data[3], "point" => array("lat" => $data[7], "lng" => $data[1])  );
        array_push($bundle, $arrInsert );
        echo var_dump($arrInsert);
        $row++;
    }
    fclose($handle);
}

$objDB->batchInsert("ff_geo_points", $bundle, true);

?>
