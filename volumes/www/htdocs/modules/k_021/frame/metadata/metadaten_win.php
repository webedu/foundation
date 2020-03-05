<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--<?php
	include "../php/get_metadata.php";
	$metadata=get_metadata("../../metadata/wm_lom.xml");
?>-->

<html>

<head>
	<title>WEBGEO-Lernmodul: <?php echo $metadata['title']['data']; ?></title>
	<link type="text/css" rel="stylesheet" href="metadaten.css">
</head>

<body onLoad="if (window.focus) self.focus()">
	<table id="structure_table" width="800">
		<tr>
			<td id="items_td">
				<h2>Lernmodul "<?php echo $metadata['title']['data']; ?>"</h2>
				<table cellpadding="2" cellspacing="1" border="0" width="100%" id="items_table">
					<colgroup>
						<col width="200"/>
						<col width="600"/>
					</colgroup>
					<?php
						$my_items = array ('author','date','publisher');
						foreach($my_items as $item){
							echo "<tr class='metadata_item'>";
							echo 	"<td>".$metadata[$item]['label']."</td>";
							echo 	"<td>".$metadata[$item]['data']."</td>";
							echo "</tr>";
						}
						print (
							"<tr class='metadata_item'>
								<td>Webadresse</td>
								<td><a target='_blank' href='http://www.webgeo.de/".strtolower($metadata['cat_1_main']['data'])."'>www.webgeo.de/".strtolower($metadata['cat_1_main']['data'])."</a></td>
							</tr>
							<tr class='metadata_item'>
								<td>Lizenzinformationen</td>
								<td><a target='_blank' href='http://www.webgeo.de/impressum'>www.webgeo.de/impressum</a></td>
							</tr>"
						);
					?>
				</table>
			</td>
		</tr>
		<tr id="bottom_line">
			<td>
				<a href="javascript:window.close()">Fenster schließen</a>
			</td>
		</tr>
	</table>
</body>

</html>