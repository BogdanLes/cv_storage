<?php header("Content-Type: application/json; charset=UTF-8");?>

<?php

require 'classescv.php';

$obj = json_decode($_POST["x"], false);
$new_country=$obj->country;
$new_country=trim($new_country);
$new_country=ucwords($new_country);

$dbfile="dbcountry.xml";
$op=Country::add_new_country($dbfile, $new_country);

//country is new
if($op) 
{
	
	$html_country="";
	$ac=[];
	$kc=0;
	$country_db_xml_file="dbcountry.xml";
	$ac=Country::load_db_country($country_db_xml_file);
	$ac=Country::sort_country($ac);

	foreach($ac as $item_country)
	{
		$html_country=$html_country."<option value='".$item_country->get_country()."'>".$item_country->get_country()."</option>";
	}

	
	$ares=array("sres"=>"new", "option"=>$html_country);
	$res=json_encode($ares);
	echo $res;
	die();
}

//country exists
$ares=array("sres"=>"xxx");
$res=json_encode($ares);
echo $res; 

?>



