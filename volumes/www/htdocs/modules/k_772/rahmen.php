<?php
	// Works only if requested from a module page WITHIN the iframe
	$rahmen_query_strg="";
	foreach ($_GET as $key=>$val){
		$rahmen_query_strg.="$key=$val&";
	}
	header("Location: iframe_content.php?fromrahmen=true&$rahmen_query_strg");
?>