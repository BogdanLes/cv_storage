<?php header("Content-Type: text/html; charset=UTF-8");?>

<?php

$scv_nr=$_POST["editcvnr"]; //cv number in database

$bss=$_POST["editcvcompany"];
$bss=htmlspecialchars_decode($bss);
//$bss=htmlspecialchars($bss);
$link=$_POST["editcvlink"];
$link=htmlspecialchars_decode($link);
//$link=htmlspecialchars($link);
$contact=$_POST["editcvcontact"];
$contact=htmlspecialchars_decode($contact);
//$contact=htmlspecialchars($contact);
$obs=$_POST["editcvobs"];
$obs=htmlspecialchars_decode($obs);
//$obs=htmlspecialchars($obs);
$date=$_POST["editcvdate"];
$date=htmlspecialchars_decode($date);
//$date=htmlspecialchars($date);
$country=$_POST["editcvc"];
$country=htmlspecialchars_decode($country);
//$country=htmlspecialchars($country);
$status=$_POST["editcvstatus"];
$status=htmlspecialchars_decode($status);
//$status=htmlspecialchars($status);
$interview=$_POST["editcvinterview"];
$interview=htmlspecialchars_decode($interview);
//$interview=htmlspecialchars($interview);


$xml=simplexml_load_file("dbcv.xml");
$nr_total_cv=$xml->cvnr;
if($scv_nr>$nr_total_cv) 
{ 
echo "<html>
<body>
<div style='color: darkread;'>The CV with this number does not exists: ".$scv_nr."</div>
</body>
</html>"; 
die();
}

$nodev="bss".$scv_nr;
if(!isset($xml->database->$nodev))
{ 
echo "<html>
<body>
<div style='color: darkread;'>This CV does not exists: ".$scv_nr."</div>
</body>
</html>"; 
die();
}


$xml->database->$nodev->bss=$bss;
$xml->database->$nodev->link=$link;
$xml->database->$nodev->contact=$contact;
$xml->database->$nodev->obs=$obs;
$xml->database->$nodev->date=$date;
$xml->database->$nodev->country=$country;
$xml->database->$nodev->status=$status;
$xml->database->$nodev->interview=$interview;

$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
$dom->save("dbcv.xml");

//echo "<div>Test done!-- ".$obs."</div>";



echo "
<html>
<head>

<script>
function loadcveditsave()
{
	parent.updateeditcv('".$scv_nr."');
}
</script>
</head>
<body onload='loadcveditsave()'>
<div>CV number".$scv_nr." edited!</div>
</body>
</html>
";

?>



