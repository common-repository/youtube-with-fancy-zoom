function ywfz_submit()
{
	if((document.ywfz_form.ywfz_title.value).trim()=="")
	{
		alert(g_ywfz_adminscripts.ywfz_title);
		document.ywfz_form.ywfz_title.focus();
		return false;
	}
	else if((document.ywfz_form.ywfz_watch.value).trim()=="")
	{
		alert(g_ywfz_adminscripts.ywfz_watch);
		document.ywfz_form.ywfz_watch.focus();
		return false;
	}
	else if((document.ywfz_form.ywfz_code.value).trim()=="")
	{
		alert(g_ywfz_adminscripts.ywfz_code);
		document.ywfz_form.ywfz_code.focus();
		return false;
	}
	/*else if((document.ywfz_form.ywfz_imglink.value).trim()=="")
	{
		alert("Please enter the image link.");
		document.ywfz_form.ywfz_imglink.focus();
		return false;
	}*/
	else if(document.ywfz_form.ywfz_img.value=="")
	{
		alert(g_ywfz_adminscripts.ywfz_img);
		document.ywfz_form.ywfz_img.focus();
		return false;
	}	
	else if(document.ywfz_form.ywfz_status.value=="")
	{
		alert(g_ywfz_adminscripts.ywfz_status);
		document.ywfz_form.ywfz_status.focus();
		return false;
	}
	else if(document.ywfz_form.ywfz_sidebar.value=="")
	{
		alert(g_ywfz_adminscripts.ywfz_sidebar);
		document.ywfz_form.ywfz_sidebar.focus();
		return false;
	}
}
String.prototype.trim = function() 
{
	return this.replace(/^\s+|\s+$/g,"");
}


function ywfz_redirect()
{
	window.location = "options-general.php?page=youtube-with-fancy-zoom";
}

function ywfz_help()
{
	window.open("http://www.gopiplus.com/work/2010/07/18/youtube-with-fancy-zoom/");
}

function ywfz_delete(id)
{
	if(confirm(g_ywfz_adminscripts.ywfz_delete))
	{
		document.frm_ywfz_display.action="options-general.php?page=youtube-with-fancy-zoom&ac=del&did="+id;
		document.frm_ywfz_display.submit();
	}
}	