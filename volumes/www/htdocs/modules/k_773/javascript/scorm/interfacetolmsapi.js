//<!--
// Michael Wild 2007-06-08
//
// Calls for the SCORM-required API function (one-to-one implementation)
// ExternalInterface.call only allows the parameters "function" and "object", but the object may contain all (function) parameters necessary
//
function Initialize () {
	return API.Initialize("");
}
function Terminate () {
	return API.Terminate("");
}
function SetValue (object) {
	return API.SetValue(object.parametername,object.parametervalue);
}
function GetValue (object) {
	return API.GetValue(object.parametername);
}
function Commit () {
	return API.Commit("");
}
function GetLastError () {
	return API.GetLastError();
}
function GetErrorString (errorcode) {
	return API.GetErrorString(errorcode);
}
function GetDiagnostic (parameter) {
	return API.GetDiagnostic(parameter);
}
// -------
// Supporting fcts
function prompt_get_value () {
	var element	= prompt("GetValue (element)",	"element");
	var alert_strg="GetValue("+element+") returns:";
	var value_strg="";
	var error_strg="";
	alert_strg+="\n\n";
	if (API) {
		value_strg=API.GetValue(element);
		error_strg="Errorcode: "+API.GetLastError()+"\n\n"+API.GetErrorString(GetLastError())+"\n\n"+API.GetDiagnostic();
	}
	if (value_strg){
		alert_strg+=value_strg;
	} else {
		alert_strg+="undefined (or similar)";
	}
	alert_strg+="\n\n";
	if (error_strg){
		alert_strg+=error_strg;
	} else {
		alert_strg+="No error message available";
	}
	alert(alert_strg);
}
function prompt_set_value () {
	var element	= prompt("SetValue (element)",	"element");
	var value	= prompt("SetValue (value)",	"value");
	var output_strg="SetValue("+element+","+value+") returns:\n"+API.SetValue(element,value)+"\n\nErrorcode: "+API.GetLastError()+"\n\n"+API.GetErrorString(API.GetLastError())+"\n\n"+API.GetDiagnostic();
	alert(output_strg);
}
function doTerminate () {
	if (API) {
		Terminate();
	}
}
//
GetAPI(window);
GetAPI(opener.window);
//-->