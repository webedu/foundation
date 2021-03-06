<?php

    session_start();
	// Include supporting php files and parse structure.xml to obtain the module's metadata

	include ("../../common/php/xmlparser.php");
	include ("../../common/php/flash/get_iframe_query_string.php");
	include ("../../common/php/get_metadata.php");
    include ("../../common/php/mail_and_log.php"); logModule();

	$flashDirExists = file_exists("flash");
	$contentsDirExist = file_exists("contents");
	$pagesDirExist = file_exists("pages");

	$useFlash = (!array_key_exists('flash',$_COOKIE) || empty($_COOKIE['flash']) || ($_COOKIE["flash"] == "available"));
	$useRuffle = (array_key_exists('flash',$_COOKIE) && !empty($_COOKIE['flash']) 
	           && (($_COOKIE["flash"] == "missing") || ($_COOKIE["flash"] == "ruffle")));
	$xmlvars = null;
	if(file_exists("structure.xml")) {
 	  $xmlvars = parsexml_metadata("structure.xml");
	}
	$metadata = ['title' => ['data' => 'Webgeo Module']];
	if(file_exists("metadata/wm_lom.xml")) {
	  $metadata=get_metadata("metadata/wm_lom.xml");
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<?php
 if($useFlash && !$pagesDirExist && $flashDirExists && !$contentsDirExist) {
    include("../../common/php/flash/index_flash.php");	 
 } else if ($useFlash && !$pagesDirExist && !$flashDirExists && $contentsDirExist) {
    include("../../common/php/flash/index_contents.php");	 
 } else if ($useRuffle && !$pagesDirExist && ($flashDirExists || $contentsDirExist)) {
    include("../../common/php/flash/index_ruffle.php");	 
 } else if ($useFlash && $pagesDirExist && ($flashDirExists || $contentsDirExist)) {
    include("../../common/php/flash/index_mixed_flash.php");	 
 } else if ($useRuffle && $pagesDirExist && ($flashDirExists || $contentsDirExist)) {
    include("../../common/php/flash/index_mixed_ruffle.php");		 
 } else if ($pagesDirExist && !$flashDirExists && !$contentsDirExist) {
    include("../../common/php/flash/index_pages.php");	 
 } else {
    include("../../common/php/flash/index_ruffle.php"); // should not happen...	 
 }
?>

</html>