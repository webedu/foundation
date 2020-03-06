<!-- index_contents.php -->
<head>
	<!--
		*********************
		** WebKit Freiburg **
		*********************
		
		Dieses Lernmodul wurde mit dem Webkit Freiburg erstellt.
		
		Powered by WebKit Freiburg, Rechenzentrum Universität Freiburg, 2002-2009:
			Reiner Fuest
			Detlev Degenhardt
			Stefan Meiershofer
			Seung Young Yang
			Michael Wild
		
		Danke auch an das Projekt WEBGEO, von dem umfangreiche Vorarbeiten stammen.
		
	-->
	
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta name="DC.contributor" content="WebKit Freiburg: Reiner Fuest, Detlev Degenhardt, Stefan Meiershofer, Seung Young Yang & Michael Wild">
	
	<!-- Metadaten nach IEEE LOM -->
	<link rel="ieeelommetadata" type="application/ieeelom+xml" title="LOM Metadata" href="metadata/wm_lom.xml">
	
	<title><?php echo $metadata['title']['data']; ?></title>
	
	<script type="text/javascript" src="javascript/scorm/getAPI_functions.js"></script>
	<script type="text/javascript" src="javascript/scorm/interfacetolmsapi.js"></script>
	
	<!--
		
		*************************
		** WEBGEO-Spezial-Code **
		*************************
		
		Für ins WebKit migrierte WEBGEO-Module, falls Parameter übergeben werden, die eigentlich an die rahmen.php (in der alten Struktur) übergeben werden sollten. Das ist z.B. bei Glossarlinks oder bei internen Links zu anderen Modulen der Fall.
		
		INTERNE LINKS
		Die GET-Variable "string" enthält entweder (a) SLOW-ID, BLOW-ID, PAGE-ID (z.B. ?string=1;k_201;1) oder (b) zusätzlich den Language-Code (z.B. ?string=de;1;k_201;1). Dies wird einfach aufgelöst und eine neue universelle URL erzeugt und geladen. Das Prinzip ist entweder (a) http://www.webgeo.de/index.php?id=k_201 oder (b) http://www.webgeo.de/k_201 . Wir verwenden Prinzip (b).
		
		GLOSSAR-LINKS
		Noch zu implementieren. Kommt in diesem Modul (k_201) nicht vor.
		
	-->
	<!--... also los (INTERNE-LINKS):-->
	<script type="text/javascript">
		var url = document.URL;
		if (url.search(/string=/)!=-1) { 
			var string=url.substr(url.indexOf("string=")+7);
			var vars_array = string.split(";");
			// Entweder blowid ist auf Index "1" oder "2" (siehe oben "INTERNE LINKS")
			if (vars_array[0]=="de" || vars_array[0]=="en") {
				var blowid=vars_array[2];
			} else {
				var blowid=vars_array[1];
			}

			var targeturl="http://www.webgeo.de/"+blowid+"/";
			if (parent) {
				parent.location.replace (targeturl);
			} else {
				window.location.replace (targeturl);
			}
		}
	</script>
	<!--... GLOSSAR-LINKS:-->
	<script type="text/javascript">
		function myopen (gbegriff) {
			gbegriff = gbegriff.toLowerCase();
			gbegriff = gbegriff.replace(/ä/g,"ae");
			gbegriff = gbegriff.replace(/ö/g,"oe");
			gbegriff = gbegriff.replace(/ü/g,"ue");
			gbegriff = gbegriff.replace(/ß/g,"ss");
			gbegriff = gbegriff.replace(/ /g,"_");
			gbegriff = gbegriff.replace(/#/g,"");
			if (gbegriff=="") {
				var url='http://www.wiki.uni-freiburg.de/webgeo/doku.php?id=glossar:start';
			} else {
				var url='http://www.wiki.uni-freiburg.de/webgeo/doku.php?id=glossar:'+gbegriff;
			}
			window.open(url,'Glossar');
		}
	</script>
	
	<link rel="stylesheet" type="text/css" href="css/webkit-styles.css">
	<link rel="shortcut icon" type="image/x-icon" href="materials/favicon.ico">
	
</head>

<body onunload=doTerminate(); >
	<div id="background-frame">
		<div id="content">
			<!--Embed the WebKit_mother.swf - begin-->
			<object data="WebKit_mother.swf" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="950px" height="620px" id="WebKit_frame">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="WebKit_mother.swf" />
				<param name="quality" value="high" />
				<embed src="WebKit_mother.swf" quality="high" width="950px" height="620px" name="WebKit_frame" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"/>
			</object><!--Embed the WebKit_mother.swf - end-->
		</div>
	</div>
    <img src='../image?module=<?= $metadata['id']['data'][0] ?>' />
</body>














