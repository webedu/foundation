<?php
	/*
		*********************
		** WebKit Freiburg **
		*********************
		
		The template registration page, the related PHP-scripts as well as CSS-styles were contributed to the WebKit Freiburg by Dr. Michael Rudner
		Created 2009-10-16 by Dr. Michael Rudner
		Modified by Michael Wild 2009-10-21
		
	*/
	
	// SETTINGS
	// Pfad zur Konfigurationsdatei mit Zugangsdaten für DB
	$configpath = "";  
  
  
	// FUNCTIONS
	function alleFelder() {
		$username = $_POST['username'];
		$pwd1     = $_POST['pwd'];
		$pwd2     = $_POST['pwd_check'];
		
		return (!empty($username) && !empty($pwd1) && !empty($pwd2));
	}
	
	function is_user_unique($username, $db) {
		$username = trim($username);
		// Make query string for user-field in usertable
		$query_string="SELECT * FROM `$db[usertable]` WHERE `learner_id` = '$username';";
		$result=mysql_query($query_string);
		$resultrow=mysql_fetch_array($result);
		if ($resultrow) {
			// Eintrag vorhanden? --> schlecht
			return false;
		} else {
			// Kein Eintrag vorhanden --> gut
			return true;
		}
	}
	
	function pwd_gleich($Pw1, $Pw2) {
		return($Pw1 === $Pw2);
	}
	
	function register_user($entries,$db){
		// Make query string
		$query_string = "INSERT INTO `".$db['usertable']."` ";
		
		//*******************
		//** Eigene Felder **
		//*******************
		
		// 1. Fields in the database (usertable)
		$query_string .= "(learner_id , pw) ";
		//$query_string .= "(learner_id , pw, hochschule) ";
		// Bsp.: mit zusätzlichem Feld 'hochschule' ersetzen Sie die erste Zeile durch die zweite
		
		$query_string .= "VALUES ";
		
		// 2. Entries passed by the script
		// NOTE: PWDs are encoded via MD5 algorithm to ensure that admins don't access the pwds in plain
		$query_string .= "('$entries[username]',MD5('$entries[pwd]'));";
		//$query_string .= "('$entries[username]','$entries[pwd]','$entries[hochschule]');";
		// Bsp.: mit zusätzlichem Feld 'hochschule' ersetzen Sie die erste Zeile durch die zweite
		//*******************
		
		// Perform query
		mysql_query($query_string) or die (mysql_error());
	}
	
	// SCRIPT
	
	require_once($configpath.'conf.inc.php');
	
	// The dynamic output is framed by some HTML
	
	print ('
		
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		
		<html>
		<head>
		
		<!--
			*********************
			** WebKit Freiburg **
			*********************
			
			This template registration page, the related PHP-scripts as well as CSS-styles were contributed to the WebKit Freiburg by Dr. Michael Rudner
			Created 2009-10-16 by Dr. Michael Rudner
			Modified by Michael Wild 2009-10-21
			
		-->
		
		<title>Registrierung für MultiStaR</title>
		
		<link href="../css/webkit_like_html.css" rel="stylesheet" type="text/css" title="stylesheet1">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	
		</head>

		<body>
			
			<div id="content">
			
			<!--
				
				**************************
				** Kopf im WebKit-Style **
				**************************
				
			-->
			
			<table width="800" background="../materials/img/titel_bg.png" height="73" cellpadding="0">
				<tr>
					<td width="150"><img src="../materials/img/uni_logo70.png"/></td>
					
					<!--
						
						***********
						** Titel **
						***********
						
					-->
					
					<td width="500" align="center">
						<h2>Registrierung</h2>
					</td>
					
					
					<td valign="top" td width="150">
						<!--Ihr Logo-->
						<img src="../materials/logo_dummy.jpg"/></td>
				</tr>
			</table>
			<table width="800" background="../materials/img/balken.png" cellpadding="0" height="25">
				<tr><td>&nbsp;</td></tr>
			</table>
			
			<!--
				
				*****************************************
				** Eigentliche Inhalte im WebKit-Style **
				*****************************************
				
			-->
			
			<table width="800" cellspacing="20">
			
				<tr>
					<td ><img src="../materials/img/punkt.png" width="370" height="1"/></td>
					<td><img src="../materials/img/punkt.png" width="370" height="1"/></td>
				</tr>
				<tr>
					<td colspan="2">
	');
	
	//************************************************************************************************
	if (alleFelder()){
	// Alles ausgefüllt
		$username = $_POST['username'];
		if (is_user_unique($username,$db)){
			// Nutzername eindeutig
			$pwd1     = $_POST['pwd'];
			$pwd2     = $_POST['pwd_check'];
			if (pwd_gleich($pwd1,$pwd2)){
			
				// PWD-Eindage korrekt
				// Erfolgreiches Registrieren (Eintragen in DB)
				// *****
				$entries['username']=$username;
				$entries['pwd']=$pwd1;
				
				//*******************
				//** Eigene Felder **
				//*******************
				// Beispiel: Im Formular wird das Feld 'hochschule' verwendet und dann per POST an dieses Skript gesendet. Alle Variablen für die DB werden in $entries übergeben:
				//$entries['hochschule']=$_POST['hochschule']
				//*******************
				
				
				register_user($entries,$db);
				// *****
				
				// Erfolgreiches Registrieren (Augsabe)
				echo '<h4><font color="#1111ee">Best&auml;tigung:</font></h4>';
				echo '<p>Sie wurden registriert und können sich ab sofort für die Nutzung der Module anmelden.</p>';
			} else{
				// Unterschiedliche Passworteingaben
				echo '<h4><font color="#ee1111">Fehler:</font></h4>';
				echo '<p>Kennwort und Bestätigung stimmen nicht überein.</p>';
			}
		} else {
			// Login vergeben
			echo '<h4><font color="#ee1111">Fehler:</font></h4>';
			echo '<p>Der Benutzername <b>'.$username.'</b> ist bereits vergeben. Wählen Sie bitte einen anderen Namen.</p>';
		}
	} else{
		// Nicht alles ausgefüllt
		echo '<h4><font color="#ee1111">Fehler:</font></h4>';
		echo '<p>Bitte füllen Sie alle Felder aus, um fortzufahren.</p>';
	}
	//************************************************************************************************
	print('
					</td>
					
				</tr>
				
				<tr>
					<td>
						<p><a href=javascript:back();>Zur Registrierung</a>
						<p><a href="../index.html">Zum Login</a>
					</td>
				</tr>
				
				<!-- Copyright -->
				<tr class="bottomline">
					<td colspan="2">Powered by <a href="http://portal.uni-freiburg.de/rz/elearning/werkzeuge/autorentools/webkit-freiburg/webkit-freiburg-main">WebKit Freiburg</a></td>
				</tr>
					
			</table>
			
			</div>
			
		</body>

		</html>
	');

?>