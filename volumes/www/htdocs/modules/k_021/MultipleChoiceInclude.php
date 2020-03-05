<?php

// Autor: Uwe Kohnle
// Strongly modified and adpated to new WEBGEO by Michael Wild (2009-10-13) in order of the WEBGEO2StandAlone-Migration
//
//
// Voreinstellungen
$TabellenBreite=880;
$Spalte1Breite=20;
$Spalte2Breite=500;
$Spalte3Breite=250;
$FontFarbe_Frage="000000";
$FontFarbe_Antwort="000000";
$FontFarbe_richtig="009900";
$FontFarbe_falsch="FF0000";
$FontFarbe_Summary_n="FF0000";
$FontFarbe_Summary_r="009900";
$FontFarbe_Summary_t1="FF0000";
$FontFarbe_Summary_t2="FF0000";
$FontFarbe_Summary_t3="FF0000";
$FontFarbe_Summary_t4="FF0000";
$FontFarbe_Summary_t5="FF0000";
$FontFarbe_Summary_f1="FF0000";
$FontFarbe_Summary_f2="FF0000";
$Summary_n="keine Antwort ausgewählt";
$Summary_r="alles richtig beantwortet!";        //alle richtigen und keine falschen Antworten
$Summary_t1="nicht alles richtig.";             //alle richtigen Antworten, doch zumindest eine falsche Antwort
$Summary_t2="nicht alles richtig.";             //nicht alle richtigen Antworten, doch auch keine falschen Antworten
$Summary_t3="nicht alles richtig.";             //nicht alle richtigen Antworten und zumindest eine falsche Antwort
$Summary_t4="nicht alles richtig.";             //nicht alle richtigen Antworten und alle falschen Antworten
$Summary_t5="nicht alles richtig.";             //alle richtigen Antworten und alle falschen Antworten
$Summary_f1="keine Antwort ist richtig!";       //nur falsche, jedoch nicht alle falschen Antworten
$Summary_f2="keine Antwort ist richtig!";       //nur alle falschen Antworten

//ab hier Code
$GesamtScore=0;
$GesamtPossScore=0;
$GesamtCorrect=0;
$GesamtDetails="";
$Fragenummer=1;
$correct=0;
$tries=1;
$Bestanden=0;
$texttmp[][]="";

if ($pageid=="") {
	$Seite="0";
} else {
	$Seite=$pageid;
}

if (isset($_GET['Ab'])){
	$Bestanden=$_GET['Bestanden'];
	$tries = $_GET['tries'];
}

// Schrift
$text = "<font face='Arial, Helvetica, sans-serif'><center>";

// HTTP METHOD and URL
$text .= "<form method='GET' action='rahmen.php?string=1;$blowid;$pageid'>\n";
// Weitergabe von Variablen mit GET über <form> VOR DEM PROZESSIEREN
$text .= "<input type='hidden' name='string' value='1;$folder;$pageid'>\n";
$text .= "<input type='hidden' name='Ab' value='true'>\n";

$text .= "<table width='$TabellenBreite'>\n<tr>\n<td colspan='3' ALIGN='left'><br><font color='#$FontFarbe_Frage'>";

if (isset($Frage[2])) {
    $text .= "<b>Sie haben den Test bestanden, wenn Sie ".$correctProzent."% der ";
    if ($TestTyp==0) {$text .= "Fragen v&ouml;llig richtig beantworten k&ouml;nnen.</b><br>\n";}
    if ($TestTyp==1) {$text .= "m&ouml;glichen Punkte erreichen.</b><br>\n";}
}
if ($Bestanden==1) {$text .= "Bestanden haben Sie diesen Test bereits.";}
if ($Bestanden==0 && $tries>1) {$text .= "Bislang haben Sie diesen Test nicht bestanden.";}
$text .= "&nbsp;</font></td>\n</tr>\n";

while (isset($Frage[$Fragenummer])){
	$Antwortnummer=1;
	$score=0;
	$possScore=0;
	$correct=0;
	$possCorrect=0;
	$possIncorrect=0;
	$totalCorrect=0;
	$totalIncorrect=0;
	$Details="";
	$DetailsTest="";
	$Variante=0;
	$text .= "<tr>\n<td colspan='3' ALIGN='left'><br><b><font color='#$FontFarbe_Frage'>".htmlentities($Frage[$Fragenummer])."</b>";

	while (isset($Antwort[$Fragenummer][$Antwortnummer])){
		$Details.="<A Nr=\"".$Antwortnummer."\">";
		$DetailAntwort="n";
		if ($Antwort_Punkte[$Fragenummer][$Antwortnummer]>0) {
			$possCorrect+=1;
			$GesamtPossScore+=$Antwort_Punkte[$Fragenummer][$Antwortnummer];
			$possScore+=$Antwort_Punkte[$Fragenummer][$Antwortnummer];
		}
		else {
			$possIncorrect+=1;
		}
		$texttmp[$Fragenummer][$Antwortnummer] = "<tr>\n<td width='$Spalte1Breite' valign='top' ALIGN='left'><input type='checkbox' name='Antwort_box-$Fragenummer-$Antwortnummer' value='ja'";

		if (isset($_GET['Antwort_box-'.$Fragenummer.'-'.$Antwortnummer.''])) {
			$texttmp[$Fragenummer][$Antwortnummer] .= " checked";
		}
		$texttmp[$Fragenummer][$Antwortnummer] .= "></td>\n<td width='$Spalte2Breite' valign='top' ALIGN='left'><font color='#$FontFarbe_Antwort'>";
		$texttmp[$Fragenummer][$Antwortnummer] .= htmlentities($Antwort[$Fragenummer][$Antwortnummer])."</font></td>\n<td width='$Spalte3Breite' valign='top' align='left'>";
		$texttmpMeldung[$Fragenummer][$Antwortnummer] = "";
		if (isset($_GET['Antwort_box-'.$Fragenummer.'-'.$Antwortnummer.''])) {
			$score+=$Antwort_Punkte[$Fragenummer][$Antwortnummer];
			if ($Antwort_Punkte[$Fragenummer][$Antwortnummer]>0) {
				$totalCorrect+=1;
				$DetailAntwort="r";
				$texttmpMeldung[$Fragenummer][$Antwortnummer] .= "<font color='#$FontFarbe_richtig'>";
			}
			else {
				$totalIncorrect+=1;
				$DetailAntwort="f";
				$texttmpMeldung[$Fragenummer][$Antwortnummer] .= "<font color='#$FontFarbe_falsch'>";
			}
			$texttmpMeldung[$Fragenummer][$Antwortnummer] .= htmlentities($Antwort_Meldung[$Fragenummer][$Antwortnummer])."</font>";
		}
		$texttmpMeldung[$Fragenummer][$Antwortnummer] .= "&nbsp;</td>\n</tr>\n";
		$Details.=$DetailAntwort."</A>";
		$DetailsTest.="<A Nr=\"".$Antwortnummer."\" Punkte=\"".$Antwort_Punkte[$Fragenummer][$Antwortnummer]."\" Meldung=\"".htmlentities($Antwort_Meldung[$Fragenummer][$Antwortnummer])."\">".htmlentities($Antwort[$Fragenummer][$Antwortnummer])."</A>";
		$Antwortnummer++;
	}
	if ($TestTyp==1) {$text .= " (max. ".$possScore." Punkte)";}
	$text .= "</font></td>\n</tr>\n";
	if (($totalCorrect==$possCorrect) && ($totalIncorrect==$possIncorrect) && ($possIncorrect!=0)){
		for($i=1;$i<$Antwortnummer;$i++){
			$texttmpMeldung[$Fragenummer][$i] = "<font color='#$FontFarbe_falsch'>Entscheiden Sie sich!</font></td>\n</tr>\n";
		}
	}
	for($i=1;$i<$Antwortnummer;$i++){
		$text .= $texttmp[$Fragenummer][$i];
		$text .= $texttmpMeldung[$Fragenummer][$i];
	}

	$text .= "<tr>\n<td colspan='3' align='left'>";
	if (isset($_GET['Ab'])){
		$GesamtDetails .= "<A Nr=\"".$Fragenummer."\">";
		if (($totalCorrect==0) && ($totalIncorrect==0)) {
			$text .= "<font color='#$FontFarbe_Summary_n'>".htmlentities($Summary_n);
			$GesamtDetails .= "n";
		}
		if (($totalCorrect==$possCorrect) && ($totalIncorrect==0)) {
			$correct=1;
			$GesamtCorrect+=1;
			$GesamtDetails .= "r";
			$text .= "<font color='#$FontFarbe_Summary_r'>".htmlentities($Summary_r);
		}
		if (($totalCorrect==$possCorrect) && ($totalIncorrect>0) && ($totalIncorrect<$possIncorrect)) {
			$text .= "<font color='#$FontFarbe_Summary_t1'>".htmlentities($Summary_t1);
			$GesamtDetails .= "t1";
		}
		if (($totalCorrect>0) && ($totalCorrect<$possCorrect) && ($totalIncorrect==0)) {
			$text .= "<font color='#$FontFarbe_Summary_t2'>".htmlentities($Summary_t2);
			$GesamtDetails .= "t2";
		}
		if (($totalCorrect>0) && ($totalCorrect<$possCorrect) && ($totalIncorrect>0) && ($totalIncorrect<$possIncorrect)) {
			$text .= "<font color='#$FontFarbe_Summary_t3'>".htmlentities($Summary_t3);
			$GesamtDetails .= "t3";
		}
		if (($totalCorrect>0) && ($totalCorrect<$possCorrect) && ($totalIncorrect==$possIncorrect) && ($possIncorrect!=0)) {
			$text .= "<font color='#$FontFarbe_Summary_t4'>".htmlentities($Summary_t4);
			$GesamtDetails .= "t4";
		}
		if (($totalCorrect==$possCorrect) && ($totalIncorrect==$possIncorrect) && ($possIncorrect!=0)) {
			$text .= "<font color='#$FontFarbe_Summary_t5'>".htmlentities($Summary_t5);
			$GesamtDetails .= "t5";
		}
		if (($totalCorrect==0) && ($totalIncorrect>0) && ($totalIncorrect<$possIncorrect)) {
			$text .= "<font color='#$FontFarbe_Summary_f1'>".htmlentities($Summary_f1);
			$GesamtDetails .= "f1";
		}
		if (($totalCorrect==0) && ($totalIncorrect==$possIncorrect) && ($possIncorrect!=0)) {
			$text .= "<font color='#$FontFarbe_Summary_f2'>".htmlentities($Summary_f2);
			$GesamtDetails .= "f2";
		}
		$GesamtDetails .= "</A>";
		if ($score<1) {$score=0;}
		if ($TestTyp==1 && $score==1) {$text .= " (1 Punkt)";}
		if ($TestTyp==1 && $score!=1) {$text .= " (".$score." Punkte)";}
		$GesamtScore+=$score;
	}
	$text .= "&nbsp;</font>\n</td>\n<tr>\n";

	$Fragenummer++;
}

if (isset($_GET['Ab'])){
	if (isset($Frage[2])) {
		$text .= "<tr><td colspan='3' align='left'><b><font color='#$FontFarbe_Frage'><br>";
		if ($GesamtCorrect==0){$text .= "keine Frage";}
		if ($GesamtCorrect==1){$text .= "Eine";}
		if ($GesamtCorrect>1){$text .= $GesamtCorrect;}
		if ($GesamtCorrect>=1){$text .= " von ".($Fragenummer-1)." Fragen";}
		$text .= " haben Sie richtig beantwortet";
		if ($TestTyp==0){
			if (($GesamtCorrect*100/($Fragenummer-1))>=$correctProzent) {$correct=1;}
			else {$correct=0;}
		}
		if ($TestTyp==1){
			$text .= " und dabei ".$GesamtScore;
			$text .= " von ".$GesamtPossScore." m&ouml;glichen Punkten erreicht\n";
			if (($GesamtScore*100/$GesamtPossScore)>=$correctProzent) {$correct=1;}
			else {$correct=0;}
		}
		if ($correct==1){$text .= "<font color='#$FontFarbe_Summary_r'>.<br>Sie haben den Test bestanden.";}
		if ($correct==0){$text .= "<font color='#$FontFarbe_Summary_f1'>.<br>Sie haben den Test leider nicht bestanden.";}
		$text .= "</font></b></td>\n</tr>\n<tr>\n<td colspan='3' align='left'>";
		$Variante=0;
	} else {
		$Ergebnisse[0] = $Ergebnisse[1];
	}
	if ($correct==0 || $GesamtCorrect<($Fragenummer-1)){
		$text .= "<input type='submit' value='Antworten nochmals &uuml;berpr&uuml;fen' name='Ab'>&nbsp;";
	} elseif ($correct==1 && $GesamtCorrect<($Fragenummer-1)){
		$Bestanden=1;
	}
	$tries++;
} else {
	$text .= "<tr>\n<td colspan='3' align='left'><br>&nbsp;<br>&nbsp;</td>\n</tr>\n<tr>\n<td colspan='3' align='left'>";
	$text .= "<input type='submit' value='Antworten &uuml;berpr&uuml;fen' name='Ab'>&nbsp;";
}
$text .= "\n<font color='#$FontFarbe_Frage'>";

$text .= "Dies ist Ihr ";
if ($tries==1) {$text .= "erster";}
if ($tries==2) {$text .= "zweiter";}
if ($tries==3) {$text .= "dritter";}
if ($tries==4) {$text .= "vierter";}
if ($tries==5) {$text .= "f&uuml;nfter";}
if ($tries>5) {$text .= $tries.".";}
$text .= " Versuch. ";

$text .= "</font></td>\n</tr>\n</table>\n";
// Weitergabe von Variablen mit GET über <form> NACH DEM PROZESSIEREN
$text .= "<input type='hidden' name='Bestanden' value='".$Bestanden."'>\n";
$text .= "<input type='hidden' name='tries' value='$tries'>\n";

// Einige Tags schließen
$text .= "</form></font></center>";


// AUSGABE DES ERZEUGTEN HTML-CODES
echo $text;

?>