<?php
	function get_vars_passed_strg () {
		$strg="";
		$strg.=get_vars_passed_strg_single("teilpro");
		$strg.=get_vars_passed_strg_single("teilpro_buchst");
		$strg.=get_vars_passed_strg_single("rahmen");
		$strg.=get_vars_passed_strg_single("folder");
		return $strg;
	}
	function get_vars_passed_strg_single($single_var) {
		$strg=$single_var."=$".$single_var;
		return $strg;
	}
?>
