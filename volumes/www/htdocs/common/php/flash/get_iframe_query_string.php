<?php
	function get_init_iframe_query_string ($xmlvars) {
		// return string for the iframe
		return "&pageid=".$xmlvars["pageid"]."&folder=".$xmlvars["folder"];
	}
?>