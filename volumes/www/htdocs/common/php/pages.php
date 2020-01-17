<?php

function collectPages($moduleDir)
{
  $pages = glob($moduleDir."/pages/*_*.html");
  return $pages;
}

function includePages($pages) {
  $i=0;
  $display = "";
  foreach($pages as $page) {
    $i++;
    echo "<div id='page_".$i."' class='page' style='".$display."'>\n";
    include($page);
    echo "</div>\n";
    $display = "display: none";
  }

}

function javascriptPages($pages) {
  $i=0;
  $pageIds=[]; 
  foreach($pages as $page) {
    $i++;
    $pageIds[]= "'#page_".$i."'";
  }
  $js = "pageData = {current:1, pages: [";
  $js .= implode($pageIds, ",");
  $js .= "]};";
  echo "<script>".$js."</script>\n";
}

?>
