<?php
	include "frame/php/xmlparser.php";
	include "frame/php/get_iframe_query_string.php";
	include ("frame/php/get_metadata.php");
	// GET vars:
	$pageid					= (int)$_GET["pageid"];
	if ($_GET["fromrahmen"]=="true" || $_GET["string"]) {
		$withrahmenstring=true;
		foreach ($_GET as $key=>$val) {
			if ($key!="string") {
				eval("$$key=\"$val\";");
			} elseif ($key=="string") {
				$vars_from_rahmen_string_array = explode(";",$val);
				if ($vars_from_rahmen_string_array[0] == "de" || $vars_from_rahmen_string_array[0] == "de"){
					array_shift($vars_from_rahmen_string_array);
				}
				$slowid_rahmen=(int)$vars_from_rahmen_string_array[0];
				$blowid_rahmen=(string)$vars_from_rahmen_string_array[1];
				$pageid_rahmen=(int)$vars_from_rahmen_string_array[2];
			}
		}
	}
	
	// Parse structure.xml to obtain organization data
	$xmlvars = parsexml_organization("structure.xml",$pageid);
	// Only set vars needed to determine whether to parse again (if coming from rahmen.php; due to navigation_ids)
	$folder					= $xmlvars["folder"];
	$total_pages			= $xmlvars["total_pages"];
	
	// Reset pageid if coming from rahmen.php
	if ($pageid_rahmen < $total_pages && $pageid_rahmen > (int)0 && $_GET["fromrahmen"]=="true") {
		$pageid = $pageid_rahmen;
		// Parse structure.xml with new pageid to obtain organization data AGAIN (SAME AS ABOVE)
		$xmlvars = parsexml_organization("structure.xml",$pageid);
	} else {
		//...
	}
	
	$metadata=get_metadata("metadata/module_lom.xml");
	
	// Set vars
	$folder					= $xmlvars["folder"];
	$blowid					= $folder;
	$teilpro				= $xmlvars["teilpro"];
	$teilpro_buchst			= $xmlvars["teilpro_buchst"];
	$rahmen					= $xmlvars["rahmen"];
	$content_php_file		= $xmlvars["content_php_file"];
	$navigation_ids 		= $xmlvars['navigation_ids'];
	$branching				= $xmlvars["branching"];
	$branching_string		= $xmlvars["branching"]?"true":"false";
	$total_pages			= $xmlvars["total_pages"];
	// We try to rebuild the original vars passed to the SWF (respectively to the PHP loading the SWF)
	$next_id				= $navigation_ids["next"];
	$prev_id				= $navigation_ids["prev"];
	$sessionid				= "";
	$schluessel				= "";
	$transport				= "";
	$back					= "";
	$s_id					= "1";
	$b_id					= $blowid;
	$p_id					= $pageid;
	$tveran					= "";
	$modvars				= "";
	// String for the GET request for the SWF file. Key-value pairs are separated by ";;;;". So this is not passed to the flash_wrapper as single vars but as string; this makes it easier to forward it to the page.swf
	$vars_to_swf_string		= "next_id=$next_id;;;;prev_id=$prev_id;;;;rahmen=$rahmen;;;;sessionid=$sessionid;;;;schluessel=$schluessel;;;;;transport=$transport;;;;back=$back;;;;s_id=$string_arr[1];;;;b_id=$string_arr[2];;;;p_id=$string_arr[3];;;;tveran=$tveran;;;;modvars=$modvars";
	// URL of the SWF file
	$swf_filename			= "flash/".$xmlvars["swf_filename"];
	$swf_url				= "frame/flash/page_wrapper.swf?swf_filename=$swf_filename&branching=$branching_string&vars_to_swf_string=$vars_to_swf_string";
	
	// Check if page can't be loaded
	if ($slowid_rahmen==0&&$blowid_rahmen==0&&$pageid_rahmen==8 && $_GET["fromrahmen"]=="true") {
		$content_php_file = "not_available_window.html";
	} 
	if ($blowid!=$blowid_rahmen && $withrahmenstring) {
		$load_new_frame_flag="$blowid_rahmen";
		$content_php_file = "not_available_window.html";
	}
	
	// Attach GET request to the navigation_ids to get full navigation_urls (e.g. p_002_01?var1=blabla&var2=blabla)
	foreach ($navigation_ids as $dir=>$id) {
		if ($id){
			$navigation_urls[$dir]="iframe_content.php?teilpro=$teilpro&teilpro_buchst=$teilpro_buchst&rahmen=$rahmen&folder=$folder&pageid=$id";
		}
		else {
			$navigation_urls[$dir]=false;
		}
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
	<!-- Redirect via javascript if another module was called -->
	<script type="text/javascript">
		if ("<?php echo $load_new_frame_flag; ?>") {
			parent.parent.location.replace("/<?php echo $blowid_rahmen; ?>","_self");
		}
	</script>
	<!-- Regular html head tags -->
	<title>WEBGEO iframe</title>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<link rel="stylesheet" type="text/css" href="frame/css/iframe.css">
	<!-- Include supporting javascript -->


  <script>
    window.RufflePlayer = window.RufflePlayer || {};
    // Update the paths below to the directory containing the Ruffle files on your web server.
    window.RufflePlayer.config = {
      "public_path": "../../common/vendor/ruffle/",
      "polyfills": ["static-content", "plugin-detect", "dynamic-content"]
    };
  </script>
  <script type="text/javascript" src="../../common/vendor/ruffle/ruffle.js"></script>

	<script type="text/javascript">
		//<!--
		glossary_url	= "<?php echo $xmlvars["glossary_url"]; ?>";
		glossary_form	= "<?php echo $xmlvars["glossary_form"]; ?>";
		webgeo_home		= "<?php echo $xmlvars["webgeo_home"]; ?>";
		//-->
	</script>
	<script type="text/javascript" src="frame/js/menufunctions.js"></script>
	<script type="text/javascript" src="frame/js/misc.js"></script>
	<script type="text/javascript">
		//<!--
		navigation_urls = new Object();
		<?php
			foreach ($navigation_urls as $dir=>$url) {
				echo "navigation_urls['$dir']='".($url ? $url : 0)."';";
			}
			echo "\n";
		?>
		//-->
	</script>
</head>

<body>
	<div id="location"><?php echo "Seite $pageid von $total_pages"; ?></div><!-- Seitenzahl -->
	<div id="content_wrapper"><?php include $content_php_file; ?></div><!-- Include the content file (e.g. p_002_01.php) -->
</body>

</html>
