<?php 
 include ("../../common/php/redirect.php");
 checkRedirect();
?>
<!-- index_flash.php -->
<head>
	<!-- Regular html head tags -->
	<title><?php echo $metadata['title']['data']; ?></title>
	<link rel="stylesheet" type="text/css" href="/common/css/flash/frame.css">
    <link rel="shortcut icon" type="image/x-icon" href="/common/img/favicon.ico">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<!-- Include supporting javascript files -->
	<script type="text/javascript">
		//<!--
		glossary_url	= "<?php echo $xmlvars["glossary_url"]; ?>";
		glossary_form	= "<?php echo $xmlvars["glossary_form"]; ?>";
		webgeo_home		= "<?php echo $xmlvars["webgeo_home"]; ?>";
		//-->
	</script>
	<script type="text/javascript" src="/common/js/flash/menufunctions.js"></script>
	<script type="text/javascript" src="/common/js/flash/misc.js"></script>
	<!--
		Redirect to the TYPO3-iframe
	-->
</head>

<body onLoad="if (window.focus) self.focus()" id="WEBGEO_module_frame"><!-- BODY - begin -->
	<div id="main_wrapper"><!-- main_wrapper - begin -->
		<table id="structure_table" cellspacing="0" cellpadding="0"><!-- Content structure table - begin -->
			<colgroup>
				<col width="33%"/>
				<col width="34%"/>
				<col width="33%"/>
			</colgroup>
			<tr id="logo_and_menu" width="100%"><!-- 1st row: logo and menu (home, glossary) - begin -->
				<td id="logo"><a href="javascript:open_webgeo_home();"><img src="/common/img/flash/webgeo_module_logo.gif" border="0" alt="WEBGEO Logo"/></a></td>
				<td/>
				<td id="menu">
					<a href="javascript:myopen('');"><img src="/common/img/flash/menu_button_glossary.gif" border="0" alt="Glossar"/></a>
					<a href="javascript:helpopen();"><img src="/common/img/flash/menu_button_help.gif" border="0" alt="Hilfe"/></a>
					<a href="javascript:open_webgeo_home();"><img src="/common/img/flash/menu_button_home.gif" border="0" alt="Home"/></a>
				</td>
			</tr><!-- 1st row - end -->
			<tr id="title_bar"><!-- 2nd row: title - begin -->
				<!--<td id="topic"><a href="http://www.webgeo.de/index.php?id=<?php //echo $metadata["cat_1_main"]["data"]; ?>">Themengebiet: <?php //echo $metadata["cat_1_main"]["data"]; ?></a></td>-->
				<td></td>
				<td></td>
				<td id="title"><?php echo $metadata['title']['data']; ?></td>
			</tr><!-- 2nd row - end -->
			<tr><!-- 3rd row: content page in an iframe - begin -->
				<td id="webgeo_content" colspan="3"><!-- WEBGEO CONTENT - begin -->
					<iframe src="<?php echo "/modules/".$xmlvars['folder']."/iframe_content.php?".get_init_iframe_query_string($xmlvars); ?>" width="950px" height="get_iframe_height();" onLoad="resize_iframe();" id="WEBGEO_module_iframe" name="WEBGEO_module_iframe" scrolling="no" marginheight="0" marginwidth="0" frameborder="0">
						<p>Hier sollte ein WEBGEO-Modul geladen werden. Ihr Browser kann möglicherweise keine eingebetteten Frames anzeigen.</p>
					</iframe>
				</td><!-- WEBGEO CONTENT - end -->
			</tr><!-- 3rd row - end -->
			<tr><!-- 4th row: metadata button - begin -->
				<td></td>
				<td id="bottom_metadata">
					<a href="javascript:metadaten('')">Impressum</a>
				</td>
				<td></td>
			</tr><!-- 4th row - end -->
		</table><!-- Content structure table - end -->
	</div><!-- main_wrapper - end -->
</body><!-- BODY - end -->
