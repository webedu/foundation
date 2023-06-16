<?php

function collectPages($moduleDir)
{
  $pages = glob($moduleDir."/pages/*_*.html");
  return $pages;
}

function includePages($pages) {
  $i=0;
  //$display = "";
  foreach($pages as $page) {
    $i++;
    echo "<div id='page_".$i."' class='page'>\n";
    include($page);
    echo "</div>\n";
    // $display = "display: none";  // panorama.... needs resize, hide in init instead
  }
}

function includePageSelect($pages) {
  $i=0;
  $l=count($pages);
  //$display = "";
  echo "<select class='custom-select' id='page-select'>\n";
  foreach($pages as $page) {
    $i++;
    echo "<option value='".$i."'>Page: ".$i." / ".$l."</option>\n";
    // $display = "display: none";  // panorama.... needs resize, hide in init instead
  }	
  echo "</select>\n";
}

function javascriptPages($pages) {
  $i=0;
  $pageIds=[]; 
  foreach($pages as $page) {
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
