<?php
function report($strg){
	global $reports;
	$GLOBALS['reports'][]=$strg;
	return true;
}
?>