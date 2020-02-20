function get_iframe_height (iframe) {
	return document.getElementById(iframe).contentWindow.document.body.scrollHeight;
}
function resize_iframe () {
	document.getElementById("WEBGEO_module_iframe").height = get_iframe_height("WEBGEO_module_iframe");
}