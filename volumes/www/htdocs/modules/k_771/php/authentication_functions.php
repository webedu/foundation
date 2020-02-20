<?php
function login($login, $pw, $db) {
	$arg_login = trim($login);
	$arg_pw = trim($pw);
	$query_string="SELECT * FROM `".$db['usertable']."` WHERE `learner_id` = '$login' AND `pw`='$pw'";
	$query=mysql_query($query_string);
	if (!query) {
		die ("error=Query error");
	}
	$resultrow=mysql_fetch_array($query);
	if ($resultrow) {
		return $resultrow['learner_id'];
	}
}
?>