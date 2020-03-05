function open_webgeo_home() {
	if (document.getElementById("WEBGEO_module_frame")) {
		if (window.dependent) {
			parent.open(webgeo_home,"_self");
			window.close();
		} else {
			window.open(webgeo_home);
		}
	} else if (parent.document.getElementById("WEBGEO_module_frame")) {
		if (parent.dependent) {
			parent.close();
			parent.parent.open(parent.webgeo_home,"_self");
		} else {
			parent.open(parent.webgeo_home);
		}
	}
}
function myopen(gbegriff) {
	gbegriff = gbegriff.replace(/ä/g,"ae");
	gbegriff = gbegriff.replace(/ö/g,"oe");
	gbegriff = gbegriff.replace(/ü/g,"ue");
	gbegriff = gbegriff.replace(/ß/g,"ss");
	gbegriff = gbegriff.replace(/Ä/g,"Ae");
	gbegriff = gbegriff.replace(/Ö/g,"Oe");
	gbegriff = gbegriff.replace(/Ü/g,"Ue");
	gbegriff = gbegriff.replace(/#/g,"");
	gbegriff = gbegriff.toLowerCase();
	if (gbegriff=="") {
		var url=glossary_url;
	} else {
		var url=glossary_form+gbegriff;
	}
	window.open(url,'Glossar');
}
function helpopen() {
	window.open('frame/help/hilfe_win.php','Hilfe','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=800,height=600');
}
function metadaten(b_id) {
	window.open('frame/metadata/metadaten_win.php','Metadaten','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=840,height=250');
}
function trechner() {
	window.open('./materials/calculator.php','Taschenrechner','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=305,height=345');
}
function navigation_request (dir){
	url = navigation_urls[dir];
	if (url==0) {
		url="termination_window.html";
		/*
		if (!top.top.window.close()) {
			url="termination_window.html";
		}
		*/
	}
	// Called from INSIDE iframe_content, thus use parent to target itself (not fine, but works...)
	parent.document.getElementById("WEBGEO_module_iframe").src = url;
}