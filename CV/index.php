<?php header('Content-type: text/html; charset=utf-8');?>

<?php

require 'classescv.php';

$datecv=date("Y-m-d"); //date for add cv
$res_addcv="<br/>"; //server response add cv
$db_companies="dbcv.xml";
$country_db_xml_file="dbcountry.xml";

//variables for add cv
$db_nr=0;
$name="";
$linkc="";
$contact="";
$obs="";
$country="";
$status_cv="";
$interview="";


//load form for Search
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


// verifie if i have add cv
$shtxt=""; // the search word - may apear in add form
$shcty=""; // the search country
$opcv_add="no"; //default op add cv
if(isset($_POST["addcvop"])) 
{ 
	$opcv_add=trim($_POST["addcvop"]); 
	$shtxt=trim($_POST["ashcvw"]); 
	$shcty=trim($_POST["ashcvc"]); 
}


//verifie if i have Search and performe it
$html_search="";
$shearch_cvs=[];
if(isset($_POST["shcvw"])) { $shtxt=trim($_POST["shcvw"]); }
if(strlen($shtxt)>1)
{
	//load data for add form if is case
	if($opcv_add=="no")
	{
		$name=trim($_POST["saddcvcompany"]);
		$linkc=trim($_POST["saddcvlink"]);
		$contact=trim($_POST["saddcvcontact"]);
		$obs=trim($_POST["saddcvobs"]);
		$datecv=trim($_POST["saddcvdate"]);
		$country=trim($_POST["saddcvc"]);
	}
	
	//perform search for txt
	$shtxt=trim($_POST["shcvw"]); 
	$shcty=trim($_POST["shcvc"]); 
	
	$shearch_cvs=Company::search_cv($shtxt, $shcty, $db_companies);
	$total_results=count($shearch_cvs);
	if($total_results==0) { $rz="<div class='shrezcl0'>Results: ".$total_results."</div>"; }
	else  { $rz="<div class='shrezcl1'>Results: ".$total_results."</div>"; }
	$html_search=$html_search."<div id='wwshrez'><div id='wshrez'></div>".$rz."</div>";
	for($i=0;$i<count($shearch_cvs);$i++)
	{
		$html_search=$html_search.$shearch_cvs[$i]->html_generator("search");
	}
}


//i have add CV
if($opcv_add=="yes")
{
	//save new cv to Company
	$db_nr=0;
	$name=trim($_POST["addcvcompany"]);
	$linkc=trim($_POST["addcvlink"]);
	$contact=trim($_POST["addcvcontact"]);
	$obs=trim($_POST["addcvobs"]);
	$datecv=trim($_POST["addcvdate"]);
	$country=trim($_POST["addcvc"]);
	$status_cv="on";
	$interview="no";
	
	$obj_new_cv=new Company($db_nr, $name, $linkc, $contact, $obs, $datecv, $country, $status_cv, $interview);
	$obj_new_cv->add_new_cv($db_companies);
	
	$res_addcv="<span style='background-color: darkblue;color: white;font-weight: bold;padding: 3px;'>Company added.</span>";

	//reset add cv Form
	$db_nr=0;
	$name="";
	$linkc="";
	$contact="";
	$obs="";
	$country="";
	$status_cv="";
	$interview="";
}


//load latest cv
$html_last_cv="";
$html_last_day_cv="";
$last_added_cvs=[];
$last_added_cvs=Company::load_last_cv($db_companies);

$zi=count($last_added_cvs)-1;
$html_last_cv=$last_added_cvs[$zi]->html_generator($type="add");

for($i=$zi; $i>=0; $i--)
{
	$html_last_day_cv=$html_last_day_cv." ".$last_added_cvs[$i]->html_generator("add");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Job</title>
<meta name="title" content="Job" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<meta name="robots" content="ALL" />
<meta name="googlebot" content="INDEX,FOLLOW" />
<meta name="author" content="Les Bogdan" />
<meta name="owner" content="Les Bogdan" />
<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" type="text/css" href="cvcss.css" />
<script type="text/javascript" src="cvjs.js"></script>

<script type="text/javascript">
var jf="main";
</script>

<script>
function change_country()
{
	var sc=document.getElementById("shcvc").value;
	if (typeof(Storage) !== "undefined") { sessionStorage.cvshcountry=sc; }
}
</script>

<script>
function change_country_add()
{
	var sc=document.getElementById("addcvc").value;
	if (typeof(Storage) !== "undefined") { sessionStorage.cvaddcountry=sc; }
}
</script>

<script>
function addnewcv()
{
	//transfer data from search Form
	document.getElementById("ashcvw").value=escapeHtml(document.getElementById("shcvw").value);
	document.getElementById("ashcvc").value=escapeHtml(document.getElementById("shcvc").value);
	
	var k=0;
	document.getElementById("addcvbtt").disabled=true;
	var vcom=document.getElementById("addcvcompany").value;
	if(vcom.length<1)
	{
		document.getElementById("addcvcompanywar").innerHTML="<span style='color: darkred;'>No company name!</span>"; k=1;
	}		
	else
	{
		document.getElementById("addcvcompanywar").innerHTML="<br/>";
	}
	var vlk=document.getElementById("addcvlink").value;
	if(vlk.length<1)
	{
		document.getElementById("addcvlinkwar").innerHTML="<span style='color: darkred;'>No company link!</span>"; k=1;
	}		
	else
	{
		document.getElementById("addcvlinkwar").innerHTML="<br/>";
	}
	var vdate=document.getElementById("addcvdate").value;
	if(vdate.length<8)
	{
		document.getElementById("addcvdatewar").innerHTML="<span style='color: darkred;'>No CV date!</span>"; k=1;
	}		
	else
	{
		document.getElementById("addcvdatewar").innerHTML="<br/>";
	}
	
	if(k==1)
	{
		document.getElementById("addcvbtt").disabled=false;
		return false;
	}
	
	document.getElementById("addcvop").value="yes";
	return true;
}
</script>

<script>
function addnewcountry()
{
	//alert("Add country");
	document.getElementById("addcybtt").disabled=true;
	var newc=document.getElementById("addcyname").value;
	var val={};
	val["country"]=newc;
	const param = JSON.stringify(val);
	//document.getElementById("addjobdirwar").innerHTML=param;
	
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onload = function() {
		const resObj=JSON.parse(this.responseText);
		
		var v=resObj.sres;
		if(v=="new")
		{
			document.getElementById("sraddcountry").innerHTML = "<span style='color: blue;'>"+newc+" added to the database.</span>";
			
			//reselect country in search and change select
			document.getElementById("shcvc").innerHTML = "<option value='All'>All Countries</option>"+resObj.option;
			if (typeof(Storage) !== "undefined") 
			{
				if (sessionStorage.cvshcountry) 
				{
					var selcty=sessionStorage.cvshcountry;
					document.getElementById("shcvc").value=selcty;
				}
			}
			
			//change select from add company Form
			document.getElementById("addcvc").innerHTML = resObj.option;
			if (typeof(Storage) !== "undefined") 
			{
				if (sessionStorage.cvaddcountry) 
				{
					var selcty2=sessionStorage.cvaddcountry;
					document.getElementById("addcvc").value=selcty2;
				}
			}
			
		}
		else
		{
			document.getElementById("sraddcountry").innerHTML = "<span>"+newc+" already in the database!</span>";
		}
		document.getElementById("addcybtt").disabled=false;
	}
	xmlhttp.open("POST", "addcountry.php");
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("x=" + param);
	
	return false;
}
</script>

<script>
function versearch()
{
	//transfer data from add cv
	document.getElementById("saddcvcompany").value=escapeHtml(document.getElementById("addcvcompany").value);
	document.getElementById("saddcvlink").value=escapeHtml(document.getElementById("addcvlink").value);
	document.getElementById("saddcvcontact").value=escapeHtml(document.getElementById("addcvcontact").value);
	document.getElementById("saddcvobs").value=escapeHtml(document.getElementById("addcvobs").value);
	document.getElementById("saddcvdate").value=escapeHtml(document.getElementById("addcvdate").value);
	document.getElementById("saddcvc").value=escapeHtml(document.getElementById("addcvc").value);
	document.getElementById("saddcvop").value="no";
	
	return true;
}

</script>

<!--
<script>
function ldbeditcv(cv_nr)
{
	//alert(cv_nr);
	document.getElementById("editcvloadnr").value=cv_nr;
	document.getElementById("loadeditform").submit();
}
</script>
-->

<script type="text/javascript">
function loadpg()
{
	if (typeof(Storage) !== "undefined") 
	{
		//reselect country in search
		if (sessionStorage.cvshcountry) 
		{
			var selcty=sessionStorage.cvshcountry;
			document.getElementById("shcvc").value=selcty;
		}
		//reselect country in add cv
		if(sessionStorage.cvaddcountry)
		{
			var selctyadd=sessionStorage.cvaddcountry;
			document.getElementById("addcvc").value=selctyadd;
		}
	}

}
</script>

</head>

<body class="brbodycl" onload="loadpg();">
<div id="wtopbar" class="wtopbarcl">
<nav id="navbtop" class="navbtopcl">
<div id="wnavsh" class="wnavshcl"><a id="navsh" class="navshcl" href="index.php">Search/Add Jobs</a></div>
<div id="wnavbr" class="wnavbrcl"><a id="navbr" class="navbrcl" href="/CV/browse.php">Browse Jobs</a></div>
</nav>
</div>

<div id="walldata" class="walldatacl">

<table id="allt" class="alltcl"><tr id="alltr" class="alltrcl">
<td id="alltd1" class="alltd1cl">
<!-- form for searching for companies with CV sent -->
<h1 id="shh1" class="shh1cl">CV - Job Hunting</h1>

<form id="cvshform" name="cvshform" onsubmit="return versearch()" action="index.php" method="POST">

<!-- load addcv -->
<div style="display: none;">
<input type="text" id="saddcvcompany" name="saddcvcompany" onchange="veraddcvcompany()" value="" />
<input type="text" id="saddcvlink" name="saddcvlink" onchange="veraddcvlink()" value="" />
<textarea id="saddcvcontact" name="saddcvcontact" ></textarea>
<textarea id="saddcvobs" name="saddcvobs" ></textarea>
<input type="text" id="saddcvdate" name="saddcvdate" value="" />
<input type="hidden" id="saddcvc" name="saddcvc" value="" />
<input type="hidden" id="saddcvop"  name="saddcvop" value="no" />
</div>

<div id="wshcvw" class="wshcvwcl"><input type="text" id="shcvw" name="shcvw" class="shcvwcl" placeholder="Search text..." value="<?php echo $shtxt; ?>" /></div>

<div id="wshcvc" class="wshcvccl">
<select id="shcvc" name="shcvc" onchange="change_country();" class="shcvccl">
<option value="All">All Countries</option>
<?php echo $html_country; ?>
</select>
</div>

<div id="wshcvs" class="wshcvscl"><input type="submit" id="shcvsbtt" class="shcvsbttcl" value="Search Company" /></div>
</form>

<div id="wallshbssres" class="wallshbssrescl"><?php echo $html_search; ?></div>

</td>

<td id="alltd2" class="alltd2cl">
<!-- form for adding companies/ new CVs -->
<div id="wwaddcv" class="wwaddcvcl">
<div id="waddcv" class="waddcvcl">
<h2 id="addcvtitle" class="addcvtitlecl">Add New CV Form</h2>
<form id="addcvform" name="addcvform" onsubmit="return addnewcv()" method="POST" action="index.php">
<input type="hidden" id="ashcvw" name="ashcvw" value="" />
<input type="hidden" id="ashcvc" name="ashcvc" value="" />


<div class="waddcvfcl">
<label for="addcvcompany" class="addcvncl">Company:</label>
<input type="text" id="addcvcompany" name="addcvcompany" class="addcvfcl" onchange="veraddcvcompany()" value="<?php echo $name; ?>" />
<div id="addcvcompanywar"><br/></div>
</div>
<div class="waddcvfcl">
<label for="addcvlink" class="addcvncl">Link:</label>
<input type="text" id="addcvlink" name="addcvlink" class="addcvfcl" onchange="veraddcvlink()" value="<?php echo $linkc; ?>" />
<div id="addcvlinkwar"><br/></div>
</div>
<div class="waddcvfcl">
<label for="addcvcontact" class="addcvncl">Contact Info:</label>
<textarea id="addcvcontact" name="addcvcontact" class="addcvfxcl" ><?php echo $contact; ?></textarea>
<div id="addcvcontactwar"><br/></div>
</div>
<div class="waddcvfcl">
<label for="addcvobs" class="addcvncl">Observations:</label>
<textarea id="addcvobs" name="addcvobs" class="addcvfxcl" ><?php echo $obs; ?></textarea>
<div id="addcvobswar"><br/></div>
</div>
<div class="waddcvfcl">
<label for="addcvdate" class="addcvncl">Date:</label>
<input type="text" id="addcvdate" name="addcvdate" class="addcvfcl" value="<?php echo $datecv; ?>" />
<div id="addcvdatewar"><br/></div>
</div>
<div class="waddcvfcl">
<select id="addcvc" name="addcvc" onchange="change_country_add();" class="addcvccl">
<?php echo $html_country; ?>
</select>
<div id="addcvcountrywar"><br/></div>
</div>

<input type="hidden" id="addcvop"  name="addcvop" value="no" />
<div class="waddcvfcl">
<input type="submit" id="addcvbtt" name="addcvbtt" class="addcvbttcl" value="Add Company CV" />
</div>

<div id="sresaddcv" class="sresaddcv"><?php echo $res_addcv; ?></div>

</form>
</div>

<!-- last cv added -->
<div id="lastcvaddedcl">
<h3 class="lastcvaddedh3cl">Latest CV:</h3>
<?php echo $html_last_cv; ?>
</div>
</div>


<!-- form for adding a new country -->
<div id="wwaddnewcountry" class="wwaddnewcountrycl">
<div id="waddnewcountry" class="waddnewcountrycl">
<h2 id="addcytitle" class="addcytitlecl">Add New Country Form</h2>
<form id="addcyfr" name="addcyfr" onsubmit="return addnewcountry()" method="POST" action="index.php">
<div id="waddcyname" class="waddcynamecl"><input type="text" id="addcyname" name="addcyname" class="addcynamecl" placeholder="Country name" value="" /></div>
<div id="waddcybtt" class="waddcybttcl"><input type="submit" id="addcybtt" class="addcybttcl" value="Add New Country" /></div>
</form>
<div id="sraddcountry"><br/></div>
</div></div>

<!-- last cv added -->
<div id="lastcvaddeddaycl">
<h3 class="lastcvaddeddayh3cl">Latest day CVs:</h3>
<?php echo $html_last_day_cv; ?>
</div>
</div>

</td>

</tr></table>
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





