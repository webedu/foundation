<?php
	
	
		//***************************
		//** ADAPT TO YOUR NEEEDS: **
		//***************************
	// Configure the path to the conf.inc.php and to other PHP files
	// Somtetimes it's useful to use some snippets:
	//$configpath = str_replace("htdocs", "htdocs/webkit/webkitmodule/php/", $_SERVER["DOCUMENT_ROOT"]);
	$configpath = "";
	//$includedscriptspath = str_replace("htdocs", "htdocs/webkit/webkitmodule/php/", $_SERVER["DOCUMENT_ROOT"]);
	$includedscriptspath = "";
		//***************************
	
	// USE OTHER SCRIPTS
	include($includedscriptspath."xml_functions.php");
	include($includedscriptspath."phpdebugger_functions.php");
	require_once($configpath.'conf.inc.php');
	
	// VARIABLES
	$reports=array();
	$webkitconnectionobject=array();
	$webkitconnectionobject=getConnectionObj($HTTP_RAW_POST_DATA);
	
	// ACTIONS BY CONNECTIONTYPE ...
	if ($webkitconnectionobject['connectiontype']=='authentication') {
		// ... AUTHENTICATION
		include($includedscriptspath.'authentication_functions.php');
		$webkitconnectionobject['learner_id']=login($webkitconnectionobject['loginname'],$webkitconnectionobject['loginpassword'], $db);
		if ($webkitconnectionobject['usedb'] AND $webkitconnectionobject['learner_id']) {
			include($includedscriptspath.'dbgetter_functions.php');
			$status=getStatus($webkitconnectionobject,$db);
			foreach ($status as $field=>$value) {
				$webkitconnectionobject['data'][$field]=$value;
			}
		}
		$webkitconnectionobject['reports']=$reports;
		echo getXMLDoc($webkitconnectionobject);
	} else if ($webkitconnectionobject['connectiontype']=='activitylogging') {
		// ... ACTIVITYLOGGING
		include($includedscriptspath.'activitylogging_functions.php');
		$webkitconnectionobject_temp=array();
		$webkitconnectionobject_temp['connectiontype']='activitylogging';
		$webkitconnectionobject_temp['dbsuccess']=logactivity($webkitconnectionobject,$db);
		$webkitconnectionobject_temp['reports']=$reports;
		echo getXMLDoc($webkitconnectionobject_temp);
	}	else if ($webkitconnectionobject['connectiontype']=='feedback') {
		// ... FEEDBACK
		include($includedscriptspath.'dbgetter_functions.php');
		include($includedscriptspath.'generalfeedback_functions.php');
		include($includedscriptspath.'personalfeedback_functions.php');
		$webkitconnectionobject=getFeedback($webkitconnectionobject,$db);
		$webkitconnectionobject['reports']=$reports;
		echo getXMLDoc($webkitconnectionobject);
	}

?>