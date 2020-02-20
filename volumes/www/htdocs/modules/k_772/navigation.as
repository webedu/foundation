function url(richtung) {
 
  if (richtung == "prev") { id = prev_id; }
 
  else { id = next_id; }

    url = rahmen + "?string=" + id + ";" + sessionid + ";" + schluessel + ";" + transport + ";" + back;

    getURL(url, "_self");

}
rahmen = "rahmen.php";