<?php
 //error_reporting (E_ALL ^ E_NOTICE);
 include ("common/php/get_module_data.php");
 include ("common/php/mail_and_log.php");
 $topic = trim($_SERVER['REDIRECT_URL'], ' /');
 if(!is_string($topic) or strlen($topic) < 1) {
   $topic = trim($_SERVER['PATH_INFO'], ' /');
 }
 $searchTerm = $_GET['search'];
 $moduleData = getModulesMetaData();
 $subTopics = null;
 if(is_string($searchTerm) and strlen($searchTerm) >= 3) {
    $searchTerm = trim($searchTerm);
    $subTopics = getSearchTopics($searchTerm, $moduleData);
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(!isBot($agent)) {
      if ($subTopics['count']>0) {
        logIntoFile(strtolower($searchTerm), 'search_history.txt', 500, TRUE, TRUE); } else {
        logIntoFile(strtolower($searchTerm), 'search_empty.txt', 500, TRUE, TRUE); }
    }
 } else {
    $subTopics = getSubTopics($topic, $moduleData);
 }
 $trackModule = $_GET['module'];
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
  <base href="http://www.webgeo.de">

  <!-- Latest compiled and minified CSS -->
  <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" -->
  <link rel="stylesheet" href="/common/vendor/bootstrap/bootstrap.min.css" type="text/css"  w4u-type="global"> 
  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="common/js/wordcloud2.js"></script>
  <script src="common/js/flash.js"></script> 
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/main.css" media="screen" /-->
  <link rel="stylesheet" type="text/css" href="common/css/main.css" media="screen" />  
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/navigation.css" media="screen" /-->
  <!--link rel="stylesheet" type="text/css" href="fileadmin/templates/css/contents.css" media="screen" /-->

  <!--link rel="shortcut icon" type="image/x-icon" href="http://www.uni-freiburg.de/favicon.ico" /-->
  <link rel="shortcut icon" type="image/x-icon" href="common/img/favicon.ico">
  <title><?php printTitle($subTopics) ?></title>
 </head>
 
 <?php
  $state = "";
  if(array_key_exists('flash',$_COOKIE) && !empty($_COOKIE['flash'])) {
    $state = $_COOKIE["flash"];     
  }
 ?>
 
 <body>
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
	</script>
		
      </div>
      <div class="col-md-2 col-sm-1"></div>
    </div>
   
    <div class="row">
      <div class="col-md-1 col-sm-0"></div>  
      <div class="col-md-9 col-sm-11">       
        <div id="header_sub">
          <div id="rootline">Sie sind hier:&nbsp;&nbsp; 
             <?php printBreadcrums($subTopics) ?>
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
        <?php printLeftMenu($moduleData, $topic, $searchTerm) ?>
      </div>
      <div class="col-lg-6 col-md-7 col-sm-8 items">
        <?php 
            switch ($topic)
            {
                case 'impressum': printRightImpressum(); break;
                case 'kontakt': printRightKontakt();  break;
                case 'history': printRightHistory();  break;
                case 'visitors': printRightHeatMap();  break;
                case 'home': printRightHome();  break; 
                default: 
                  if(('' == $topic) and ('' == $searchTerm))
                  { printRightHome(); } 
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
       <a href="http://www.webgeo.de/">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp; 
       <a href="http://www.webgeo.de/impressum">Impressum</a>&nbsp;&nbsp;&nbsp;|&nbsp; 
       <a href="http://www.webgeo.de/kontakt">Kontakt</a>
       <span style='font-size:1px;'>&nbsp<a href="http://www.webgeo.de/history">H</a></span>        
      </span>
     </div>      
     </div>
     <div class="col-md-2 col-sm-1"></div>  
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



function printLeftMenu($moduleData, $topic, $searchTerm='')
{
  $topics = getMainTopics($moduleData);
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

function printRightHome()
{
  logIp();
  $words = getCloudTags(null);
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
  echo "<div id='sourrounding_div' style='width:100%;height:400px'>\n";
  echo " <canvas id='canvas' class='canvas' style='display: block;'></canvas>\n";
  echo " <div id='canvasbox' hidden></div> \n";    
  echo "</div>\n";
  echo "<script>\n";
  echo "var canvasSurround = document.getElementById('sourrounding_div'); \n"; 
  echo "var canvasElement = document.getElementById('canvas'); \n"; 
  echo "canvasElement.width = canvasSurround.offsetWidth; \n";
  echo "canvasElement.height = canvasSurround.offsetHeight; \n"; 
  echo "var boxit = $('#canvasbox'); \n"; 
  echo "window.drawBox = function drawBox(item, dimension) { \n";
  echo "  if (!dimension) { \n";
  echo "    boxit.prop('hidden', true); \n";
  echo "    return; \n";
  echo "  } \n";
  echo "  boxit.prop('hidden', false); \n";
  echo "  boxit.css({ \n";
  echo "    left: dimension.x + 15 + 'px', \n";
  echo "    top: dimension.y + 25 + 'px', \n";
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
  echo "        window.location.href = 'http://www.webgeo.de/?search='+item[0]; \n";
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

function printRightKontakt()
{
  echo " <h5><b>Kontakt</b></h5>\n";
  echo " <p class='small'>\n";
  echo " Institut für Umweltsozialwissenschaften und Geographie<br/>\n";
  echo " Schreiberstraße 20 <br/>\n";
  echo " 79085 Freiburg im Breisgau<br/>\n";
  echo " </p>\n";
  echo " <span class='small'>Ansprechpartner: </span><a href='https://www.geographie.uni-freiburg.de/ipg/team/saurer_helmut' target='_blank'>Dr. Helmut Saurer</a><br/>\n";
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
    printMailForm();
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
       $url = 'http://www.webgeo.de/?search='.urlencode(trim($searchTopic));
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

function printBreadcrums($subTopics)
{
  echo "<a href='http://www.webgeo.de/'>WEBGEO</a>\n";
  foreach($subTopics['breadcrums'] as $toc)
  {
    echo "&nbsp;/&nbsp;";
    $topic = urlencode(strtolower($toc));
    echo "<a href='".$topic."/'>".$toc."</a>\n";
  }
}

function printTitle($subTopics)
{
    $counting = count($subTopics['breadcrums']);
    echo ($counting > 0) ? $subTopics['breadcrums'][$counting - 1] : "WEBGEO"; 
}
?>