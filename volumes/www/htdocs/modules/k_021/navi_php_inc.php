<?php

/*

	This provides the navigation for PHP contents.
	It's included at the end of e.g. p_002_05.php (a PHP content).

*/

echo "<div>";
// Prev-button (<a> element AND graphics)
if ($prev_id) {
	echo "<a href=\"javascript:navigation_request('prev');\">";
	echo 	"<img src=\"frame/img/modul_navi_back.gif\" width=\"45px\" height=\"20px\" border=\"0\">";
	echo "</a>";
} else {
  echo "<img src=\"frame/img/modul_navi_ende.gif\" width=\"45px\" height=\"20px\" border=\"0\">";
}

// Mid line (graphics)
echo "<img src=\"frame/img/modul_navi_mitte.gif\" width=\"860px\" height=\"20px\" border=\"0\">";

// Next-button (<a> element if needed AND graphics)
if (!$branching&&$next_id) {
	echo "<a href=\"javascript:navigation_request('next');\">";
	echo 	"<img src=\"frame/img/modul_navi_next.gif\" width=\"45px\" height=\"20px\" border=\"0\">";
	echo "</a>";
}
else {
  echo "<img src=\"frame/img/modul_navi_ende.gif\" width=\"45px\" height=\"20px\" border=\"0\">";
}
echo "</div>";

?>
