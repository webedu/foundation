//<!--
// Michael Wild 2007-06-08: script taken from SCORM 1.4 3rd edition RTE book
//
var API = null;
var nFindAPITries = 0;
var maxTries = 500;
//
function ScanForAPI(win) {
	while ((win.API_1484_11 == null) && (win.parent != null) && (win.parent != win)) {
		nFindAPITries++;
		if (nFindAPITries > maxTries) {
			return null;
		}
		win = win.parent;
	}
	return win.API_1484_11;
}
function GetAPI(win) {
	if ((API == null) && (win.parent != null) && (win.parent != win)) {
		API = ScanForAPI(win.parent);
	} 
	if ((API == null) && (win.opener != null)) {
		API = ScanForAPI(win.opener);
	}
	if (API != null) {
		APIVersion = API.version;
	}
}
//-->