<?php header('Content-type: text/html; charset=utf-8');?>

<?php

require 'classescv.php';

$country_db_xml_file="dbcountry.xml";

//load counties and generate country select
$html_country="";
$ac=[];
$kc=0;
//$country_db_xml_file="dbcountry.xml";
$ac=Country::load_db_country($country_db_xml_file);
$ac=Country::sort_country($ac);

foreach($ac as $item_country)
{
	$html_country=$html_country."<option value='".$item_country->get_country()."'>".$item_country->get_country()."</option>";
}

//generate month
$month = array (
array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"),
array("January", "February", "March", "April", "May", "June", "July", "August", "September", "Octomber", "November", "December")
);
$html_month="";

$selected_month="All";
if(isset($_GET["brcvmonth"])) { $selected_month=trim($_GET["brcvmonth"]); }
for($i=0;$i<12;$i++)
{
	if($selected_month==$month[0][$i]) { $html_month=$html_month."<option value='".$month[0][$i]."' selected='selected'>".$month[0][$i]." - ".$month[1][$i]."</option>"; }
	else { $html_month=$html_month."<option value='".$month[0][$i]."'>".$month[0][$i]." - ".$month[1][$i]."</option>"; }
}

//generate sorting
$html_sort="";
$sortcv="None";
if(isset($_GET["brcvsort"])) { $sortcv=trim($_GET["brcvsort"]); }
if($sortcv=="Ascending") { $html_sort=$html_sort."<option value='Ascending' selected='selected'>Ascending</option>"; }
else { $html_sort=$html_sort."<option value='Ascending'>Ascending</option>"; }
if($sortcv=="Descending") { $html_sort=$html_sort."<option value='Descending' selected='selected'>Descending</option>"; }
else { $html_sort=$html_sort."<option value='Descending'>Descending</option>"; }

$html_sortval="";
$sortcvval="None";
if(isset($_GET["brcvsortval"])) { $sortcvval=trim($_GET["brcvsortval"]); }
$html_sortval="<option value='None'>None</option>";
if($sortcvval=="Company") { $html_sortval=$html_sortval."<option value='Company' selected='selected'>Company</option>"; }
else { $html_sortval=$html_sortval."<option value='Company'>Company</option>"; }
if($sortcvval=="Date") { $html_sortval=$html_sortval."<option value='Date' selected='selected'>Date</option>"; }
else { $html_sortval=$html_sortval."<option value='Date'>Date</option>"; }

$selected_country="no";
if(isset($_GET["brcvc"])) { $selected_country=trim($_GET["brcvc"]); }

// i have Browse
$html_browse="";
$db_companies="dbcv.xml";
if($selected_country!="no")
{
// generate iterator with companies from country
$browse_cvs=new JobsIterator($db_companies, $selected_country);

// filter by month if is the case
if($selected_month!="All")
{
	$browse_cvs->date_filter($selected_month);
}

//sort results if needed
if($sortcvval=='Company' && $sortcv=='Ascending') { $browse_cvs->sort_results($sortcvval, $sortcv); }
if($sortcvval=='Company' && $sortcv=='Descending') { $browse_cvs->sort_results($sortcvval, $sortcv); }
if($sortcvval=='Date' && $sortcv=='Ascending') { $browse_cvs->sort_results($sortcvval, $sortcv); }
if($sortcvval=='Date' && $sortcv=='Descending') { $browse_cvs->sort_results($sortcvval, $sortcv); }


//list browse results
$total_results=$browse_cvs->total_results();
if($total_results!=0) { $html_browse=$html_browse."<div id='wbrowseresults'><div id='browseresults'>Results: ".$total_results."</div></div>"; }
else { $html_browse=$html_browse."<div id='wbrowseresults'><div id='browseresults0'>Results: ".$total_results."</div></div>"; }

foreach($browse_cvs as $cv_item)
{
	$html_comp=$cv_item->html_generator("browse");
	$html_browse=$html_browse.$html_comp;
}

}
// end --- i have Browse


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CV Browse</title>
<meta name="title" content="CV Browse" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<meta name="robots" content="ALL" />
<meta name="googlebot" content="INDEX,FOLLOW" />
<meta name="author" content="Les Bogdan" />
<meta name="owner" content="Les Bogdan" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="cvcss.css" />
<script type="text/javascript" src="cvjs.js"></script>

<script>
function change_country_browse()
{
	var sc=document.getElementById("brcvc").value;
	if (typeof(Storage) !== "undefined") { sessionStorage.cvbrcountry=sc; }
}
</script>

<script>
function brload()
{
	if (typeof(Storage) !== "undefined") 
	{
		//reselect country in browse
		if (sessionStorage.cvbrcountry) 
		{
			var selcty=sessionStorage.cvbrcountry;
			document.getElementById("brcvc").value=selcty;
		}
		
	}

}
</script>
</head>
<body onload="brload()">
<div id="wtopbar" class="wtopbarcl">
<nav id="navbtop" class="navbtopcl">
<div id="wnavsh" class="wnavshcl"><a id="navsh" class="navshcl" href="index.php">Search/Add Jobs</a></div>
<div id="wnavbr" class="wnavbrcl"><a id="navbr" class="navbrcl" href="/CV/browse.php">Browse Jobs</a></div>
</nav>
</div>

<div id="walldatabr" class="walldatabrcl">

<h1 id="browseh1" class="browseh1cl">Browse Jobs</h1>

<!-- browse jobs form -->
<div id="wwbrform">
<form id="browseform" name="browseform" action="browse.php" method="GET">
<div id="wbrform">
<table id="brtable">
<tr class="brtablercl">
<td id="brtabled11" class="brtabled1cl">
<div id="brinfo">Select:</div>

<div id="wbrcountry">
<select id="brcvc" name="brcvc" onchange="change_country_browse();" class="brcvccl">
<option value="All">All Countries</option>
<?php echo $html_country; ?>
</select>
</div>

<div id="wbrmonth">
<select id="brcvmonth" name="brcvmonth" class="brcvmonthcl">
<option value="All">All Months</option>
<?php echo $html_month; ?>
</select>
</div>
</td>

<td id="brtabled21" class="brtabled2cl">
<div id="brinfosort">Sort:</div>

<div id="wbrsortval">
<select id="brcvsortval" name="brcvsortval" class="brcvsortvalcl">
<?php echo $html_sortval; ?>
</select>
</div>

<div id="wbrsort">
<select id="brcvsort" name="brcvsort" class="brcvsortcl">
<option value="None">Sort Type</option>
<?php echo $html_sort; ?>
</select>
</div>
</td>
</tr>

<tr class="brtablercl">
<td id="brtabled12" class="brtabled1cl">
<input type="submit" id="brcvsubmnitbtt" name="brcvsubmnitbtt" class="brcvsubmnitbttcl" value="Browse Jobs" /> 
</td>
<td id="brtabled22" class="brtabled2cl"></td>
</tr>
</table>
</div>
</form>
</div>
<!-- end browse jobs form -->

<div id="wallbrowseresults"><?php echo $html_browse; ?><div>

</div>


<br><br>

<!-- form for editing CVs -->
<div id="wweditcv" style="display: none;"><div id="weditcv">
<h3>Edit CV form:</h3>
<div style="display: none;">
<form id="loadeditform" name="loadeditform" action="editcvload.php" method="GET" target="sresif">
<input type="hidden" id="editcvloadnr" name="editcvloadnr" value="">
<input type="submit" id="editcvloadbtt" name="editcvloadbtt" value="Load CV data" />
</form>
</div>

<form id="editcvform" name="editcvform" action="editcvsave.php" method="POST" target="sresif">
<input type="hidden" id="editcvnr" name="editcvnr" value="">

<div class="weditcvfcl">
<label for="editcvcompany" class="editcvncl">Company:</label>
<input type="text" id="editcvcompany" name="editcvcompany" class="editcvfcl" onchange="vereditcvcompany()" value="" />
<div id="editcvcompanywar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvlink" class="editcvncl">Link:</label>
<input type="text" id="editcvlink" name="editcvlink" class="editcvfcl" onchange="vereditcvlink()" value="" />
<div id="editcvlinkwar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvcontact" class="editcvncl">Contact Info:</label>
<textarea id="editcvcontact" name="editcvcontact" class="editcvfxcl" ></textarea>
<div id="editcvcontactwar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvobs" class="editcvncl">Observations:</label>
<textarea id="editcvobs" name="editcvobs" class="editcvfxcl" ></textarea>
<div id="editcvobswar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvdate" class="editcvncl">Date:</label>
<input type="text" id="editcvdate" name="editcvdate" class="editcvfcl" value="" />
<input type="button" id="editupdate" onclick="editupdatef()" value="Update" />
<div id="editcvdatewar"><br/></div>
</div>
<div class="weditcvfcl">
<select id="editcvc" name="editcvc" class="editcvccl">
<?php echo $html_country; ?>
</select>
<div id="editcvcountrywar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvstatus" class="editcvncl">Status:</label>
<input type="text" id="editcvstatus" name="editcvstatus" class="editcvfcl" onchange="vereditcvstatus()" value="" />
<div id="editcvstatuswar"><br/></div>
</div>
<div class="weditcvfcl">
<label for="editcvinterview" class="editcvncl">Interview:</label>
<input type="text" id="editcvinterview" name="editcvinterview" class="editcvfcl" onchange="vereditcvinterview()" value="" />
<div id="editcvinterviewwar"><br/></div>
</div>

<div class="weditcvfbcl">
<input type="submit" id="editcvbtt" name="editcvbtt" class="editcvbttcl" value="Edit Company CV" />
<input type="button" id="editclear" onclick="closeedit()" value="Close" />
</div>
</form>



<div id="wsres" class="wsrescl" style="display: block;">
<iframe id="sresif" name="sresif" class="sresifcl" style="width: 90%;height: 200px;"></iframe>
</div>
</div></div>

</body>
</html>



