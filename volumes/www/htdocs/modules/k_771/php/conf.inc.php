<?php

	$db['server']="localhost";
	//$db['server']="myql.ruf.uni-freiburg.de";
	$db['dbuser']="webkit";     //Username für DB eintragen
	$db['password']="***";   //PWD für DB eintragen
	$db['dbname']="webkit";
	$db['usertable']="user";
	$db['useractivities']="useractivities";
	$db['courseactivities']="courseactivities";

	$db['connection']=mysql_connect($db['server'],$db['dbuser'],$db['password']) or die(mysql_error());
	$db['selecteddb']=mysql_select_db($db['dbname'],$db['connection']) or die(mysql_error());

?>
