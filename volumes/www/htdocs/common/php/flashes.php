<?php

function collectFlashes($moduleDir, $flashDir="flash")
{
  $flashes = glob($moduleDir."/".$flashDir."/*_*.swf");
  return $flashes;
}

function includeFlashes($flashes) {
  $i=0;
  $display = "";
  foreach($flashes as $flash) {
    $i++;
    echo "<div id='page_".$i."' class='page' >\n";
    // include($flash);
	
    echo " <div class='container-fluid'>\n";
    echo "  <div class='row' style='margin: 8px;'>\n";
    echo "   <div class='col-sm-0 col-md-0 col-lg-1 col-xl-2'></div>\n";
    echo "   <div class='col-sm-12 col-md-11 col-lg-10 col-xl-7'>\n"; 
    echo "    <object data='".$flash."' \n"
        ."      classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' \n" 
        ."      codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' \n" 
        ."      width='950' height='520' \n"
		."      id='module_page'>\n";
    echo "     <param name='allowScriptAccess' value='sameDomain'>\n";
    echo "     <param name='movie' value='".$flash."'>\n";
    echo "     <param name='quality' value='high'>\n";
    echo "     <param name='bgcolor' value='#ffffff'>\n";
    echo "     <embed src='".$flash."' \n" 
	    ."      quality='high' width='950' height='520' \n" 
	    ."      bgcolor='#ffffff' name='module_page' \n"
	    ."      allowscriptaccess='sameDomain' \n" 
	    ."      type='application/x-shockwave-flash' \n" 
	    ."      pluginspage='http://www.macromedia.com/go/getflashplayer'>\n";
    echo "    </object>\n";
    echo "   </div> <!-- end md-10 -->\n";
    echo "   <div class='col-sm-0 col-md-1 col-lg-1 col-xl-3'></div>\n";
    echo "  </div> <!-- end row -->\n";
    echo " </div> <!-- end container -->\n"; 
    echo "</div>\n";
  }
}

function includeFlashSelect($flashes) {
  $i=0;
  $l=count($flashes);
  //$display = "";
  echo "<select class='custom-select' id='page-select'>\n";
  foreach($flashes as $page) {
    $i++;
    echo "<option value='".$i."'>Page: ".$i." / ".$l."</option>\n";
    // $display = "display: none";  // panorama.... needs resize, hide in init instead
  }	
  echo "</select>\n";
}

function javascriptFlashes($flashes) {
  $i=0;
  $pageIds=[]; 
  foreach($flashes as $flash) {
    $i++;
    $pageIds[]= "'#page_".$i."'";
  }
  $js = "pageData = {current:1, pages: [";
  $js .= implode(",",$pageIds);
  $js .= "]};";
  $js .= " initPages();";
  echo "<script>".$js."</script>\n";
}

?>
