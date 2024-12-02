
/*
/all files
*/

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

//edit cv load data for cv
function ldbeditcv(cv_nr)
{
	document.getElementById("wweditcv").style.display="block";
	document.getElementById("editcvloadnr").value=cv_nr;
	document.getElementById("loadeditform").submit();
}

function updateeditcv(cv_nr)
{
	var i=1;
	var t, x , vx;
	for(i=1;i<=3;i++)
	{
		if(i==1) { t="vsh"+cv_nr; }
		if(i==2) { t="vadd"+cv_nr; }
		if(i==3) { t="vbr"+cv_nr; }
	
		x=t+"title";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvcompany").value); }
		x=t+"link";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvlink").value); }
		x=t+"contact";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvcontact").value); }
		x=t+"obs";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvobs").value); }
		x=t+"country";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvc").value); }
		x=t+"date";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvdate").value); }
		x=t+"status_cv";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvstatus").value); }
		x=t+"interview";
		if(document.getElementById(x))
		{ document.getElementById(x).innerHTML=escapeHtml(document.getElementById("editcvinterview").value); }
	
	}
	
}

function editupdatef()
{
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0');
	var yyyy = today.getFullYear();

	var uptoday = yyyy + '-' + mm + '-' + dd;
	document.getElementById("editcvdate").value=uptoday;
}

function closeedit()
{
	document.getElementById("wweditcv").style.display="none";
}

