<?php header("Content-Type: text/html; charset=UTF-8");?>

<?php

$lcv_nr=$_GET["editcvloadnr"];

//echo "<div>Test done!-- ".$lcv_nr."</div>";

$xml=simplexml_load_file("dbcv.xml");
$nr_total_cv=$xml->cvnr;
if($lcv_nr>$nr_total_cv) 
{ 
echo "<html>
<body>
<div style='color: darkread;'>The CV with this number does not exists: ".$lcv_nr."</div>
</body>
</html>"; 
die();
}

$nodev="bss".$lcv_nr;
if(!isset($xml->database->$nodev))
{ 
echo "<html>
<body>
<div style='color: darkread;'>This CV does not exists: ".$lcv_nr."</div>
</body>
</html>"; 
die();
}

$bss=$xml->database->$nodev->bss;
$bss=htmlspecialchars_decode($bss);
$link=$xml->database->$nodev->link;
$link=htmlspecialchars_decode($link);
$contact=$xml->database->$nodev->contact;
$contact=htmlspecialchars_decode($contact);
$obs=$xml->database->$nodev->obs;
$obs=htmlspecialchars_decode($obs);
$date=$xml->database->$nodev->date;
$date=htmlspecialchars_decode($date);
$country=$xml->database->$nodev->country;
$country=htmlspecialchars_decode($country);
$status=$xml->database->$nodev->status;
$status=htmlspecialchars_decode($status);
$interview=$xml->database->$nodev->interview;
$interview=htmlspecialchars_decode($interview);

echo "
<html>
<head>
<script>
var cvnr='".htmlspecialchars($lcv_nr)."';
var bss='';
var link1='';
var contact='';
var obs='';
var date1='';
var country='';
var status1='';
var interview='';
</script>

<script>
function loadcvedit()
{
	bss=document.getElementById('bssdv').value;
	link1=document.getElementById('linkdv').value;
	contact=document.getElementById('contactdv').value;
	obs=document.getElementById('obsdv').value;
	date1=document.getElementById('datedv').value;
	country=document.getElementById('countrydv').value;
	status1=document.getElementById('statusdv').value;
	interview=document.getElementById('interviewdv').value;
	
	parent.document.getElementById('editcvnr').value=cvnr;
	parent.document.getElementById('editcvcompany').value=bss;
	parent.document.getElementById('editcvlink').value=link1;
	parent.document.getElementById('editcvcontact').value=contact;
	parent.document.getElementById('editcvobs').value=obs;
	parent.document.getElementById('editcvdate').value=date1;
	parent.document.getElementById('editcvc').value=country;
	parent.document.getElementById('editcvstatus').value=status1;
	parent.document.getElementById('editcvinterview').value=interview;
}
</script>
</head>
<body onload='loadcvedit()'>
<div>Loaded data for business: ".$bss."</div>
<div>Database number: ".$lcv_nr."</div>

<input type='text' id='bssdv' value='".htmlspecialchars($bss)."' /><br/>
<input type='text' id='linkdv' value='".htmlspecialchars($link)."' /><br/>
<textarea id='contactdv'>".htmlspecialchars($contact)."</textarea><br/>
<textarea id='obsdv'>".htmlspecialchars($obs)."</textarea><br/>
<input type='text' id='datedv' value='".htmlspecialchars($date)."' /><br/>
<input type='text' id='countrydv' value='".htmlspecialchars($country)."' /><br/>
<input type='text' id='statusdv' value='".htmlspecialchars($status)."' /><br/>
<input type='text' id='interviewdv' value='".htmlspecialchars($interview)."' />

</body>
</html>
";


?>



