<?php
// ini_set('display_errors', '1');
// error_reporting (E_ALL ^ E_NOTICE);
 include ("common/php/get_module_data.php");
 include ("common/php/mail_and_log.php");
 include ("common/php/redirect.php");
 $host = 'www.webgeo.de';
 if(array_key_exists('HTTP_HOST', $_SERVER)) {
   $host = trim($_SERVER['HTTP_HOST'], ' /');
 }
 $protocol = stripos($_SERVER['REQUEST_SCHEME'],'https') === 0 ? 'https://' : 'http://';
 $topic = '';
 if(array_key_exists('REQUEST_URI', $_SERVER)) {
   $topic = trim($_SERVER['REQUEST_URI'], ' /');
 }
 if(array_key_exists('REDIRECT_URL', $_SERVER)) {
   $topic = trim($_SERVER['REDIRECT_URL'], ' /');
 }
 if(!is_string($topic) or strlen($topic) < 1) {
   $topic = trim($_SERVER['PATH_INFO'], ' /');
 }
 //print($topic);
 checkRedirect();	  
 $searchTerm = array_key_exists('search', $_GET) ? $_GET['search'] : null;
 $stringTerm = array_key_exists('string', $_GET) ? $_GET['string'] : null;
 $errorTerm = array_key_exists('error', $_GET) ? $_GET['error'] : null;
 $isoMode = array_key_exists('ISO', $_GET) ? $_GET['ISO'] : null;
 $moduleData = getModulesMetaData(null, $protocol, $host);
 $subTopics = null;
 if(is_string($searchTerm) and strlen($searchTerm) >= 3) {
    $searchTerm = trim($searchTerm);
    $subTopics = getSearchTopics($searchTerm, $moduleData, $protocol, $host);
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(!isBot($agent)) {
      if ($subTopics['count']>0) {
        logIntoFile(strtolower($searchTerm), 'search_history.txt', 500, TRUE, TRUE); } else {
        logIntoFile(strtolower($searchTerm), 'search_empty.txt', 500, TRUE, TRUE); }
    }
 } else {
    $subTopics = getSubTopics($topic, $moduleData, 0, $protocol, $host);
 }
 $trackModule = array_key_exists('module', $_GET) ? $_GET['module'] : null;
 if('image'==$topic and is_string($trackModule) and strlen($trackModule) >= 3) {
  //echo(' '.$trackModule.' ');  
  logModule($trackModule); 
  header('Content-Type: image/png');
  die(hex2bin('89504e470d0a1a0a0000000d494844520000000100000001010300000025db56ca00000003504c5445000000a77a3dda0000000174524e530040e6d8660000000a4944415408d76360000000020001e221bc330000000049454e44ae426082'));
 }

?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" --> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?= $protocol ?><?= $host ?>">
  <link rel="shortcut icon" href="common/icons/favicon.ico" type="image/x-icon">
  <!-- Latest compiled and minified CSS -->
  <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" -->
  <link rel="stylesheet" href="common/vendor/bootstrap/bootstrap.min.css" type="text/css"  w4u-type="global"> 
  <link href="common/vendor/fonts/awesome.min.css" rel="stylesheet" type="text/css" w4u-type="global">
  <!-- jQuery library -->
  <script src="common/vendor/jquery/jquery.min.js"></script>
  <!-- Popper & Bootstrap JS -->
  <script src="common/vendor/bootstrap/popper.min.js" type="text/javascript"></script>
  <script src="common/vendor/bootstrap/bootstrap.bundle.min.js" type="text/javascript"></script>
  <script src="common/js/wordcloud2.js"></script>
  <script src="common/js/flash.js"></script> 
  <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/main.css" media="screen" /-->
  <link rel="stylesheet" type="text/css" href="common/css/main.css" media="screen" />  
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/navigation.css" media="screen" /-->
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/contents.css" media="screen" /-->

  <link rel="stylesheet" type="text/css" href="common/vendor/cookie/cookieconsent.min.css" /> 


  <!--link rel="shortcut icon" type="image/x-icon" href="http://www.uni-freiburg.de/favicon.ico" /-->
  <link rel="shortcut icon" type="image/x-icon" href="common/img/favicon.ico">
  <title><?php printTitle($subTopics) ?></title>
 </head>
 
 <?php
  $state = "";
  if(array_key_exists('flash',$_COOKIE) && !empty($_COOKIE['flash'])) {
    $state = $_COOKIE["flash"];     
  }
  /*
  if($isoMode) {
	$state = "available";  // kiosk 
  }
  */
 ?>
 
 <body>
    <script src="common/vendor/cookie/cookieconsent.min.js"></script>
	  <script>
	  // https://www.osano.com/cookieconsent/documentation/javascript-api/
   window.cookieconsent.initialise({
    container: document.getElementById("content"),
	content: {
		href: 'impressum',
		message: 'Diese Webseite benutzt Cookies um die Bedienung zu verbessern.',
        dismiss: 'OK',
		link: 'Mehr Informationen',
		// close: '&#0000FF;',
	},
    palette:{
     popup: {background: "#fff"},
     button: {background: "#0000aa"},
    },
    revokable:true,
    onStatusChange: function(status) {
     console.log(this.hasConsented() ?
      'enable cookies' : 'disable cookies');
    },
    law: {
     regionalLaw: false,
    },
    location: true,
   });
  </script>
  <div class="container-fluid">
    <p style='font-size:1px;'>&nbsp;</p>
    <!--begin of header -->
    <div class="row">
      <div class="col-md-1 col-sm-0"></div>
       <div class="col-md-3 col-sm-4 menu">
        <?php 
          echo "<div id='navigation'>\n";
          echo " <ul class='navi'>\n";
          printSearchMenu($searchTerm);
          echo " </ul> <!-- end of navi -->\n";
          echo "</div> <!-- end of navigation -->\n";
        ?>
      </div>
      <div class="col-md-6 col-sm-7">
        <img style="margin-bottom:10px;" class='float-right' src="/common/img/webgeo_header.gif">
		
        <a href="https://helpx.adobe.com/flash-player.html" target="flash" class="float-right flash-plugin" style="display: none;">
          <img width="100px" height="66px" src="/common/img/flash.jpg" style="margin: 0px 0px;">
            <span class="float-right" style="font-size: 90%; margin: 0px 10px"><span style="color: red;">Plugin für</span><br/><b>Flash Player</b><br/><span style="color: red;">wird benötigt</span></span>
        </a>
		
		<span class="float-right flash-used" style="display: none;">
          <img width="100px" height="66px" src="/common/img/flash.jpg" style="margin: 0px 0px;">
		  <span class="float-right" style="font-size: 90%; margin: 0px 10px">Nutzt <b>Adobe Player</b><br/>für Flash Inhalte<br/>
		              <span style="color: blue;" class="ruffle-use" style="di2splay: none;" onclick='switchToRuffle(); return false'>Zu Ruffle wechseln</span>
          </span>
        </span>		  

		<span class="float-right ruffle-used" style="display: none;">
          <a href="http://ruffle.rs/" target="flash"><img width="100px" height="66px" src="/common/img/ruffle.png" style="margin: 0px 0px;"></a>
		  <span class="float-right" style="font-size: 90%; margin: 0px 10px">Nutzt <b>Ruffle</b><br/>für Flash Inhalte<br/>
		              <span style="color: blue;" class="flash-use" style="display: none;" onclick='switchToAdobe(); return false'>Zu Adobe wechseln</span>
          </span>
        </span>			  
		
	<script>
		var state = initFlash("<?= $state ?>");
        showFlash(state);
		$( document ).ready(function() { showKioskModal(); });
	</script>
		
      </div>
      <div class="col-md-2 col-sm-1"></div>
    </div>
   
    <div class="row">
      <div class="col-md-1 col-sm-0"></div>  
      <div class="col-md-9 col-sm-11">       
        <div id="header_sub">
          <div id="rootline">Sie sind hier:&nbsp;&nbsp;  
             <?php printBreadcrums($subTopics, $protocol, $host) ?>
          </div>
          <div id="zoominfo">
            <a href="javascript:alert(&quot;[Strg] &amp; [+]: Vergrößern\n[Strg] &amp; [-]: Verkleinern\n[Strg] &amp; [0]: Reset\n\n[Crtl] &amp; [+]: Zoom in \n[Crtl] &amp; [-]: Zoom out \n[Crtl] &amp; [0]: Reset&quot;)">Zoom</a>
          </div>
        </div>
      </div>
      <div class="col-md-1 col-sm-1">
        <div id="unilogo"><!--###unilogo### begin -->
          <a href="http://www.uni-freiburg.de/" target="_self" class="logo_link"><img src="/common/img/uni_logo.png"></a>
        </div>   
      </div>  
      <div class="col-md-1 col-sm-0"></div>                
    </div> <!--row end -->                
    <!--end of header --> 
    <p style='font-size:1px;'>&nbsp;</p>
    <div class="row">
      <div class="col-md-1 col-sm-0"></div>
      <div class="col-md-3 col-sm-4 menu">
        <?php printLeftMenu($moduleData, $topic, $searchTerm, $protocol, $host) ?>
      </div>
      <div class="col-lg-6 col-md-7 col-sm-8 items">
        <?php 
            switch ($topic)
            {
                case 'impressum': printRightImpressum(); break;
                case 'kontakt': printRightKontakt($errorTerm);  break;
                case 'history': printRightHistory();  break;
                case 'visitors': printRightHeatMap();  break;
                case 'home': printRightHome($protocol, $host);  break; 
                default: 
                  if(('' == $topic) and ('' == $searchTerm))
                  { printRightHome($protocol, $host); } 
                  else
                  { printRightModules($subTopics); } 
            }
          ?>
      </div>
      <div class="col-lg-2 col-md-1 col-sm-0"></div>
    </div>
    
    <div class="row">   
     <div class="col-md-1 col-sm-0"></div>  
     <div class="col-md-9 col-sm-11"> 
     <div id="header_sub"  class="text-center">     
      <span class="small">
       <a href="<?= $protocol ?><?= $host ?>">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp; 
       <a href="<?= $protocol ?><?= $host ?>/impressum">Impressum</a>&nbsp;&nbsp;&nbsp;|&nbsp; 
       <a href="<?= $protocol ?><?= $host ?>/kontakt">Kontakt</a>
       <span style='font-size:1px;'>&nbsp<a href="<?= $protocol ?><?= $host ?>/history">H</a></span>        
      </span>
     </div>      
     </div>
     <div class="col-md-2 col-sm-1"></div>  
    </div>     
    
  </div>
  
<div class="modal" tabindex="-1" id="kiosk" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lernumgebung WEBGEO - Kiosk Modus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div><p><img src="/lernkiosk/webgeo_logo_lernkiosk_klein.jpg"></p></div>
		<p>Glückwunsch zur erfolgreichen Nutzung von <strong>WEBGEO</strong> mit Hilfe der Applikation <strong>WEBGEO lernkiosk</strong>. Diese Applikation wird eingesetzt, um Flash Inhalte in <strong>WEBGEO</strong> auch dann anzeigen zu können, wenn auf dem eigenen Rechner kein Flash Plugin verfügbar ist oder Flash Inhalte geblockt werden.</p>
		<p>Hinweise zur Bedienung und zum Konzept von <strong>WEBGEO lernkiosk</strong> findest du <a href="lernkiosk/bedienung">HIER</a>.</p>
		<p>Die Nutzung von <strong>WEBGEO lernkiosk</strong> erolgt unter der <a href="lernkiosk/lizenz"><strong>Educational Community License, Version 2.0 (ECL-2.0)</strong></a>.</p>
      </div>
    </div>
  </div>
</div>  
  
  
 </body>
</html> 




<?php
function printSearchMenu($searchTerm = '')
{
  $active0 = (is_string($searchTerm) and strlen($searchTerm) >= 3) ? 'active' : ''; 
  echo "  <li>\n"; 
  echo "   <label class='".$active0."'>WEBGEO search</label>\n";  
  echo "   <ul class='subnavi'>\n";
  echo "    <li>\n";       
  echo "     <form method='get' action='/'>\n";
  echo "      <input type='search'  name='search' value='".htmlspecialchars($searchTerm, ENT_QUOTES, "UTF-8")."'>\n";
  echo "      <button type='submit' class='float-right'>Los</button>\n";
  echo "     </form>\n"; 
  echo "    </li>\n"; 
  echo "   </ul>\n";    
  echo "  </li>\n";      
}

function printMiscMenu()
{
  echo "  <li>\n";
  echo "   <a href='https://wiki.uni-freiburg.de/webgeo/doku.php?id=glossar:start' target='_blank'>WEBGEO glossar</a>\n";
  echo "  </li>\n";
  echo "  <li>\n";
  echo "   <a href='https://wiki.uni-freiburg.de/webgeo/doku.php?id=community:start' target='_blank'>WEBGEO community</a>\n";
  echo "  </li>\n";
}



function printLeftMenu($moduleData, $topic, $searchTerm='', $protocol='http://', $host='www.webgeo.de')
{
  $topics = getMainTopics($moduleData, $protocol, $host);
  echo "<div id='navigation'>\n";
  echo " <ul class='navi'>\n";
  //printSearchMenu($searchTerm);
  foreach($topics as $topic0)
  {
    $active0 = $topic0['topic'] == $topic ? 'active' : ''; 
    echo "  <li>\n";
    echo "   <a href='".$topic0['topic']."/' class='".$active0."'>".$topic0['label']."</a>\n";
    echo "   <ul class='subnavi'>\n";
    foreach($topic0['tokens'] as $topic1)
    {
      $active1 = $topic1['topic'] == $topic ? 'active' : ''; 
      // $active1 = 'ac2tive'; 
      echo "  <li><a href='".$topic1['topic']."/' class='".$active1."'>".$topic1['label']."</a></li>\n";
    }
    echo "   </ul>\n";
    echo "  </li>\n";
  }
  //printMiscMenu();
  echo " </ul> <!-- end of navi -->\n";
  echo "</div> <!-- end of navigation -->\n";
}

function printRightHome($protocol='http://', $host='www.webgeo.de')
{
  logIp();
  $words = getCloudTags(null, $protocol, $host);
  $history = inqSearchHistory(50);
  foreach($history['history'] as $search)
  { $words[$search] += 6.5; }
  $history = inqSearchHistory(100);
  foreach($history['history'] as $search)
  { $words[$search] += 5.5; }
  $tmp = [];
  foreach($words as $key=>$value)
  { $tmp[$key] = -1.0 * $value * sqrt($value) * strlen($key); }
  array_multisort($tmp, $words);
  $jsArray = "[";
  foreach($words as $word=>$size)
  {
      if(($size > 1) and (strlen($word) < 50) and (strlen($word) > 1) and ('dummy-keyword 1' != $word) and ('fao' != $word))
      {
      if($size > 15) {$size = 15;}  
      $size = $size / (2.0 + strlen($word));
      $jsArray .= "['".$word."', ".(7.5+5.0*$size)."],";
      }
  }
  $jsArray .= "]";  
  echo " <h5 class='text-center'><b>WEBGEO</b></h5>\n";
  echo " <p class='small alertBox'>Seit der Einstellung des Adobe Flash Players seit dem 1.1.2021 läuft diese Seite unter dem Flash-Emulator Ruffle. Da sich dieser noch in der Entwicklung befinden, kann es dabei allerdings zu Problemen kommen. Bitte nutzen sie die Möglichkeit, Fehler direkt auf der betroffenen WEBGEO-Seite zu melden (bitte für jede Seite einen einzelnen Report).<br/><br/>Alternativ können sie die WEBGEO-Module auch in einem <a href='/lernkiosk/bedienung' target='lernkiosk'><b>Lernkiosk</b></a> nach einer lokalen <a href='/lernkiosk/installation' target='lernkiosk'><b>Installation</b></a> nutzen.</p>\n";
  echo "<div id='sourrounding_div' style='width:100%;height:400px'>\n";
  echo " <canvas id='canvas' class='canvas' style='display: block;'></canvas>\n";
  echo " <div id='canvasbox' hidden></div> \n";    
  echo "</div>\n";
  echo "<script>\n";
  echo "var canvasSurround = document.getElementById('sourrounding_div'); \n"; 
  echo "var canvasElement = document.getElementById('canvas'); \n"; 
  echo "canvasElement.width = canvasSurround.offsetWidth; \n";
  echo "canvasElement.height = canvasSurround.offsetHeight; \n"; 
  echo "var canvasOffset = 0 + canvasElement.offsetTop - 1; \n";
  echo "if(isKioskMode()) { canvasOffset = 25; } \n";
  echo "var boxit = $('#canvasbox'); \n"; 
  echo "window.drawBox = function drawBox(item, dimension) { \n";
  echo "  if (!dimension) { \n";
  echo "    boxit.prop('hidden', true); \n";
  echo "    return; \n";
  echo "  } \n";
  echo "  boxit.prop('hidden', false); \n";
  echo "  boxit.css({ \n";
  echo "    left: dimension.x + 15 + 'px', \n";
//  echo "    top: dimension.y + 25 + 'px', \n";
  echo "    top: dimension.y + canvasOffset + 'px', \n";  
  echo "    width: dimension.w + 2 + 'px', \n";
  echo "    height: dimension.h + 2 + 'px' \n";
  echo "  }); \n";
  echo "}; \n";    
  echo "var words = ".$jsArray."; \n"; 
  echo "  WordCloud([canvasElement], { \n";
  echo "    list: words, \n";
  echo "      shape: 'circle', \n"; 
  echo "      gridSize: 4, \n";
  if (mt_rand(0,10) >= 5) {
    echo "      rotationSteps: 40, \n";   
    echo "      rotateRatio: 0.85, \n"; 
    echo "      minRotation: -1.570796, \n";     
  } else {
    echo "      rotationSteps: 2, \n";   
    echo "      rotateRatio: 0.55, \n"; 
    echo "      minRotation: 0.0, \n";     
  }
  echo "      maxRotation: +1.570796, \n";    
  echo "      shuffle: true, \n"; 
  echo "      color: 'random-dark', \n";  
  echo "      hover: window.drawBox, \n";    
  echo "      click: function(item) { \n";
  echo "        window.location.href = '<?= $protocol ?><?= $host ?>/?search='+item[0]; \n";
  echo "      } \n";   
  echo "  } \n"; 
  echo "  ); \n";
  echo "</script>\n"; 
  echo " <p class='small'>Webgeo ist eine multimedial gestaltete Lernumgebung, in der Prozessabfolgen der Physischen Geographie modelliert und visualisiert werden. Kurze, miteinander vernetzte interaktive Lerneinheiten bilden eine wissensorientierte Lehr-/Lernumgebung, die das selbstgesteuerte Erarbeiten von themenspezifischem Grundlagenwissen ermöglicht. Durch Vernetzung der Module werden komplexe Zusammenhänge zwischen den Teildisziplinen aufgezeigt.</p>\n";
  
}

function printRightImpressum()
{
  echo " <h5><b>Impressum</b></h5>\n";
  echo " <p class='small'>Dieses Impressum bezieht sich auf alle Bestandteile des Online-Angebotes von WEBGEO:</p>\n";
  echo " <h6>Herausgeber</h6>\n";
  echo " <p class='small'>\n";
  echo "Institut für Umweltsozialwissenschaften und Geographie<br/>\n";
  echo "Schreiberstraße 20 <br/>\n";
  echo "79085 Freiburg im Breisgau\n";
  echo " </p>\n";
  echo " <h6>Verantwortlichkeit</h6>\n";
  echo " <p class='small'>Die Inhalte der einzelnen Module liegen in der Verantwortung der genannten Autoren.</p>\n";
}

function printRightKontakt($errorTerm=null)
{
  echo " <h5><b>Kontakt</b></h5>\n";
  echo " <p class='small'>\n";
  echo " Institut für Umweltsozialwissenschaften und Geographie<br/>\n";
  echo " Schreiberstraße 20 <br/>\n";
  echo " 79085 Freiburg im Breisgau<br/>\n";
  echo " </p>\n";
  echo " <span class='small'>Ansprechpartner: </span><a href='https://www.geographie.uni-freiburg.de/de/professuren/physische-geographie/team-und-kontakt/saurer-helmut' target='_blank'>Dr. Helmut Saurer</a><br/>\n";
  echo " <p class='small'>Tel.: +49 - (0) 761 - 203 - 3537<br/>\n";
  echo " Fax: +49 - (0) 761 - 203 - 3596<br/>\n";
  echo " </p>\n";  
  echo " <span class='small'>Web: </span><a href='http://www.geographie.uni-freiburg.de/' target='_blank'>www.geographie.uni-freiburg.de</a><br/>\n";
  
  //echo " <br/><a href='mailto:helmut.saurer@geographie.uni-freiburg.de?subject=Anfrage%20WEBGEO'>Anfrage per E-Mail</a>\n";
  $errorInMail = true;
  if ($_POST) {
    $errorInMail = sendMail($_POST);
  } 
  if ($errorInMail)
  {
    printMailForm($errorTerm);
  } else {
    echo "<br/><p> Mail gesendet </p>";    
  }
}

function printRightHistory()
{
    //$lastSearchTopics = inqSearchHistory();
    
    $all = inqSearchHistory(24);
    //$lastSearchTopics = $all['history'];
    
    echo " <h5><b>Search History</b></h5>\n";
    
    echo " <div class='row'>\n";
    echo "  <div class='col-md-6'>\n";
    echo " <h6>With Results</h6>\n";
    foreach($all['history'] as $searchTopic)
    {
       $url = '<?= $protocol ?><?= $host ?>/?search='.urlencode(trim($searchTopic));
       echo "<a href='".$url."'>".htmlspecialchars($searchTopic, ENT_QUOTES, "UTF-8")."</a><br/>\n";
    }
    echo "  </div>\n";
    echo "  <div class='col-md-6'>\n";
    echo " <h6>Without Results</h6>\n";
    foreach($all['empty'] as $searchTopic)
    {
       if(strlen($searchTopic) < 70) {
          //$url = 'http://www.webgeo.de/?search='.urlencode(trim($searchTopic));
          echo "<span class='small'>".htmlspecialchars($searchTopic, ENT_QUOTES, "UTF-8")."</span><br/>\n";
        }
    }    
    echo "  </div>\n";
    echo " </div> \n"; 
    
}

function printRightHeatMap()
{
  $visitors = inqVisitors(500); // max=5000;
  //echo " <h5 class='text-center'><b>Visitors</b></h5>\n"; 
  echo " <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0/leaflet.css'/> \n";
  echo " <script src='//cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0/leaflet.js'></script> \n";
  echo " <script src='common/js/heatmap.min.js'></script> \n"; 
  echo " <script src='common/js/leaflet-heatmap.js'></script> \n"; 
  echo " <div id='map' style='height:500px;'></div> \n"; 
  echo " <script> \n"; 
  echo " var baseLayer = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', "
     ." { attribution: '...', maxZoom: 18, minZoom: 1, noWrap: true }); \n";
  echo " var heatData = { max: 4, data: [ \n"; 
  foreach($visitors as $visitor) { 
    echo "{lat: ".$visitor['lat'].", lng:".$visitor['lng'].", value: ".$visitor['value']."},";
  }    
  echo " ] }; \n";
  echo " var cfg = { 'radius': 25, 'maxOpacity': .8, 'scaleRadius': false, 'useLocalExtrema': false}; \n";  
  echo " var heatmapLayer = new HeatmapOverlay(cfg); \n"; 
  echo " var map = new L.Map('map', { center: new L.LatLng(51,12), zoom: 4, zoomSnap: 0.0, layers:[baseLayer, heatmapLayer]}); \n";  
  echo " heatmapLayer.setData(heatData); \n"; 
  echo " </script> \n";   
}

function printRightModules($subTopics)
{
    //var_dump($subTopics);
    if(is_array($subTopics) && array_key_exists('data', $subTopics)) {
      foreach ($subTopics['data'] as $toc0=>$data1)
      {
        if ('' != $toc0) { echo "<h3>".$toc0."</h3>\n"; }
        foreach ($data1 as $toc1=>$data2)
        {
            if ('' != $toc1 && $toc0 != $toc1) { echo "<h4><b>".$toc1."</b></h4>\n"; }
            foreach ($data2 as $toc2=>$data3)
            {
                if ('' != $toc2 && $toc1 != $toc2) { echo "<h5><b>".$toc2."</b></h5>\n"; }
                foreach ($data3 as $toc3=>$data4)
                {
                    if ('' != $toc3 && $toc2 != $toc3) { echo "<h6>".$toc3."</h6>\n"; }
                    echo "<hr/>\n";                    
                    foreach ($data4 as $item)
                    {
                        echo "<a href='".$item['url']."' data-toggle='tooltip' title='".$item['description']."'>".$item['title']."</a>\n";
                        /*
                        $urlWiki = 'http://www.wiki.uni-freiburg.de/webgeo/doku.php?id=community:modules:'.$item['module'];
                        echo "<a href='".$urlWiki."' target='_blank' class='wiki float-right'>Wiki</a>"; 
                        */
                        //echo "<a href='' class='info float-right'>Info</a>"; 
                        
                // Not the nicest solution - and may not working in php7... 
                // Better use jquery & modal dialog
                $functionName = 'info_window_'.$item['module'];        
                echo "<script>";
				echo "function ".$functionName."() {window.open('./fileadmin/templates/materials/info_window.php?blowid=".$item['module']."','info_window','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=840,height=600');}";
				echo "</script>";
				echo "<a href='javascript:".$functionName."()' class='info float-right'>Info</a>";
                // end info-hack
                        
                        echo "<br/>\n";
                    } 
                    echo "<br/>\n";
                }     
            }     
        }    
      }
    } 
    //var_dump($subTopics);
}

function printBreadcrums($subTopics, $protocol='http://', $host='www.webgeo.de')
{
  //echo "<a href='http://www.webgeo.de/info.php'>I </a>\n";
  echo "<a href='".$protocol.$host."/'>WEBGEO</a>\n";
  if(is_array($subTopics) && array_key_exists('breadcrums', $subTopics)) {
    foreach($subTopics['breadcrums'] as $toc)
    {
      echo "&nbsp;/&nbsp;";
      $topic = urlencode(strtolower($toc));
      echo "<a href='".$topic."/'>".$toc."</a>\n";
    }
  }
}

function printTitle($subTopics)
{
    //var_dump([' BEGIN ',$subTopics,' END ']);
    $counting = 0;
    if(is_array($subTopics) && array_key_exists('breadcrums', $subTopics)) {
      $counting = countOrNull($subTopics['breadcrums']);
    }
    echo ($counting > 0) ? $subTopics['breadcrums'][$counting - 1] : "WEBGEO"; 
}
?>
