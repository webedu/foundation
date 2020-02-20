<?php
function logactivity ($webkitconnectionobject,$db) {
	$courseactivities_nr=registeractivity ($webkitconnectionobject,$db);
	$query_string="INSERT INTO `".$db['useractivities']."` ";
	
		//***************************
		//** ADAPT TO YOUR NEEEDS: **
		//***************************
		// 1. Set individual fields in the table
	$query_string.="(`id`, `learner_id`, `type`, `timestamp` ,`learner_response`, `description`, `session`) ";
	$query_string.="VALUES (";
		// 2. Fill with data from the WebKit
	$query_string.="'".$webkitconnectionobject['id']."', ";
	$query_string.="'".$webkitconnectionobject['learner_id']."', ";
	$query_string.="'".$webkitconnectionobject['type']."', ";
	$query_string.="'".$webkitconnectionobject['timestamp']."', ";
	$query_string.="'".$webkitconnectionobject['learner_response']."', ";
	$query_string.="'".$webkitconnectionobject['description']."', ";
	$query_string.="'".$webkitconnectionobject['session']."' ";
	$query_string.=");";
		//***************************
		
	report($query_string);
	$result = mysql_query($query_string);
	$resultarray = mysql_fetch_array($result);
	if ($resultarray) {
		return "true";
	} else {
		return "false";
	}
}
function registeractivity ($webkitconnectionobject,$db) {
	
		//***************************
		//** ADAPT TO YOUR NEEEDS: **
		//***************************
		// (Only needed if you change courseactvities)
		// 1.
	$selectionquery_string="SELECT * FROM `".$db['courseactivities']."`";
	$selectionquery_string.=" WHERE `id`='".$webkitconnectionobject['id']."';";
		//
	$selectionquery=mysql_query($selectionquery_string);
	$row=mysql_fetch_array($selectionquery);
	if ($row) {
		// 2.
		$query_string="UPDATE `".$db['courseactivities']."` ";
		$query_string.="SET `timestamp_lastupdate`='".$webkitconnectionobject['timestamp']."'";
		$query_string.=" WHERE `id`='".$webkitconnectionobject['id']."'";
		//
		mysql_query($query_string);
	} else {
		// 3.
		$query_string="INSERT INTO `".$db['courseactivities']."` ";
		$query_string.="(`id`,`timestamp_lastupdate`) ";
		$query_string.="VALUES (";
		$query_string.="'".$webkitconnectionobject['id']."', ";
		$query_string.="'".$webkitconnectionobject['timestamp']."' ";
		$query_string.=");";
		//***************************
		
		$query = mysql_query($query_string);
		$selectionquery=mysql_query($selectionquery_string);
		$row=mysql_fetch_array($selectionquery);
	}
	return $row['nr'];
}
?>