<?php
    session_start();
	// Include supporting php files and parse structure.xml to obtain the module's metadata
	include ("frame/php/xmlparser.php");
	include ("frame/php/get_iframe_query_string.php");
	include ("frame/php/get_metadata.php");
    include ("../../common/php/mail_and_log.php"); logModule();
	$xmlvars = parsexml_metadata("structure.xml");
	$metadata=get_metadata("metadata/wm_lom.xml");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<?php
 if($_SESSION["flash"] == "available") {
   include("./index_flash.php");
 } else {
   include("./index_ruffle.php");
 }  
?>

</html>