<?php
function getStatus ($webkitconnectionobject, $db) {
	$data=array();
	$query_string="SELECT * FROM `".$db['courseactivities']."`;";
	$result=mysql_query($query_string);
	if ($result) {
		$i=0;
		while ($resultrow=mysql_fetch_array($result)) {
			$data[$i]=array();
			$data[$i]=getStatus_singleActivity ($webkitconnectionobject, $db, $resultrow['id']);
			$i++;
		}
	}
	return $data;
}
function getStatus_singleActivity ($webkitconnectionobject, $db, $courseactivity) {
	$data=array();
	$query_string="SELECT * FROM `".$db['useractivities']."` ";
	$query_string.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string.="AND `id`='".$courseactivity."' ";
	$query_string.="ORDER BY `nr` DESC LIMIT 1;";
	$result=mysql_query($query_string);
	if ($result) {
		$resultrow=mysql_fetch_array($result);
		
		//***************************
		//** ADAPT TO YOUR NEEEDS: **
		//***************************
		// Set individual fields in the WebKit with data from the table
		$data['id']=$resultrow['id'];
		$data['learner_id']=$resultrow['learner_id'];
		$data['type']=$resultrow['type'];
		$data['timestamp']=$resultrow['timestamp'];
		$data['learner_response']=$resultrow['learner_response'];
		$data['description']=$resultrow['description'];
		$data['session']=$resultrow['session'];
		//***************************
		
	}
	return $data;
}
?>