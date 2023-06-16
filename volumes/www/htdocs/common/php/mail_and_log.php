<?php

function removeCounter($str) 
{
   $parts = explode(':', $str);
   $sliced = array_slice($parts, 0, -1); 
   $string = implode(":", $sliced);
   return $string;
}

function removeBrackets($str) { 
  $str = preg_replace('/(^\s*\()|(\)\s*$)/', '', $str);
  $str = preg_replace('/(^\s*\()|(\)\s*$)/', '', $str);
  return $str;
}

function inqLogFile($fileName='webgeo_log.txt', $maxEntries = 100, $removeCounter = FALSE)
{
  $filePath = $_SERVER["DOCUMENT_ROOT"].'/../var/log/'.$fileName; 
  $lineList = [];
  if(file($filePath)) {
    $lineList = array_filter(array_map("trim", file($filePath)));
  }
  if ($removeCounter) {
    $lineList = array_map("removeCounter", $lineList);  
  }
  $lineList = array_map("removeBrackets", $lineList);
  $lineList = array_slice($lineList, 0, --$maxEntries);  
  return array_filter($lineList);
}

function inqSearchHistory2($maxEntries = 50)
{
  $history = [];  
  $maximum = 1.0;
  $history2 = inqLogFile('search_history.txt', $maxEntries, FALSE); 
  foreach($history2 as $histLine)
  {
    $value = (int)array_pop(explode(':', $histLine));  
    $history[removeCounter($histLine)] = $value;
    if($value > $maximum) {$maximum = $value;}  
  }
  $empty = inqLogFile('search_empty.txt', $maxEntries, TRUE);
  return ['history' => $history, 'empty' => $empty, 'maximum' => $maximum];    
}

function inqSearchHistory($maxEntries = 50)
{
  $history = inqLogFile('search_history.txt', $maxEntries, TRUE);
  $empty = inqLogFile('search_empty.txt', $maxEntries, TRUE);
  return ['history' => $history, 'empty' => $empty];
}

function rndGeo($r = 0.1)
{ return 2.0*$r*(mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax() -0.5);
}

function inqVisitors($maxEntries = 500, $value = 100.0)
{
   $result = []; 
   $users = inqLogFile('all_users.txt', $maxEntries);
   $maxEntries = count($users);
   foreach($users as $user) {
      $data1 = explode(';', $user);
      $data2 = explode(',', $data1[3]);
      $result[] = ['lat' => $data2[0]+rndGeo(0.01), 'lng' => $data2[1]+rndGeo(0.01), 'value' => (int)$value];
      $value = $value * (1.0 - 1.0/$maxEntries);  
      //$value = $value * 0.1;  
   }
   return $result;
}

function logIntoFile($entry, $fileName='webgeo_log.txt', $maxEntries = 100, $unique = FALSE, $counting = FALSE)
{
  if(is_string($entry) and strlen($entry) >= 3) {  
    $oldEntry = trim($entry);
    $newEntry = trim($entry);    
    $filePath = $_SERVER["DOCUMENT_ROOT"].'/../var/log/'.$fileName;
    $lineList = [];
    if(file_exists($filePath)) {
      $lineList = array_filter(array_map("trim", file($filePath)));
    }
    $new = (false === array_search(trim($entry), $lineList));
    if($counting) { 
      $found = preg_grep("/^".trim($entry).":\d+$/", $lineList);
       if(is_array($found) and sizeof($found) > 0)
      {
        $new = FALSE;
        $oldEntry = trim(array_pop(array_values($found)));
        $lineList = array_diff( $lineList, $found );
        $parts = explode(':', $oldEntry);
        $oldNumber = (int)array_pop($parts);
        $newEntry = trim($entry).':'.($oldNumber+1);
      } else {
        $newEntry = trim($entry).':1';
        $oldEntry = $newEntry;
        $new = TRUE;
      }          
    }
    if ($unique) {
      $lineList = array_diff( $lineList, [$oldEntry] );
    }
    $lineList = array_slice($lineList, 0, $maxEntries);
    array_unshift($lineList, $newEntry);
    file_put_contents($filePath, implode(PHP_EOL, array_filter($lineList)));
    return $new;
  }
  return NULL;
}

function logModule($topic = NULL)
{
  if(!is_string($topic) or strlen($topic) < 1) { 
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
	if($_COOKIE["flash"] == "available") {
        if ($_COOKIE["kiosk"] == "done") {
		    $topic .= '-K';
		} else {
			$topic .= '-F';
		}
	} else if(($_COOKIE["flash"] == "missing") or ($_COOKIE["flash"] == "ruffle")) {
		$topic .= '-R';
	} else {
        $topic .= '-U';
	}		
  }
  $agent = $_SERVER['HTTP_USER_AGENT'];
  if(!isBot($agent)) {
    logIntoFile($topic, 'all_modules.txt', 1000, TRUE, TRUE);  
  }
}

function logIp()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $new = logIntoFile($ip, 'all_ips.txt', 500, TRUE);  //:69/6h;
    if($new) {
        
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(isBot($agent)) {
          logIntoFile($agent, 'bots.txt', 500, TRUE);
        } else {
          logIntoFile($agent, 'no_bots.txt', 500, TRUE);
		  if(array_key_exists("flash", $_COOKIE) && ($_COOKIE["flash"] == "available")) {
		    $agent .= '-F';
	      } else if(array_key_exists("flash", $_COOKIE) && (($_COOKIE["flash"] == "missing") or ($_COOKIE["flash"] == "missing"))) {
		    $agent .= '-R';
	      } else {
			$agent .= '-U'; 
		  }
          $location = getLocationByIp($ip, true); 
          //echo $location;
          if(!is_null($location) and is_string($location)) {
            $ts = date("Y-m-d H:i:s");
            logIntoFile($ts.';'.$location.';'.str_replace(';','§',$agent), 'all_users.txt', 5000, FALSE); 
            $locArray = explode(';', $location);
            logIntoFile($locArray[0], 'all_locations.txt', 500, TRUE);
            if(count($locArray) > 4 ) {
              logIntoFile($locArray[4], 'all_organisations.txt', 500, TRUE); }            
         }          
       }

    }
}

function sendMail($postData, $to = 'helmut.saurer@geographie.uni-freiburg.de')
{
  $errorInMail = false;
  $ip1 = $_SERVER['REMOTE_ADDR'];
  $ip2 = $postData['ip'];
  $sameIp = ($ip1 == $ip2);
  $mt1 = microtime(TRUE);
  $mt2 = (double)$postData['mt'];
  $mtd = $mt1 - $mt2;
  $spam = isSpam($ip1, $postData['message']);
  
  $captcha = false;
  
  if (isset($postData['g-recaptcha-response']) && !empty($postData['g-recaptcha-response'])) 
  {
	 $secret = 'aaaaaaaaaaa-bbbbbbbbbbbbbbbbbbbb'; 
     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$postData['g-recaptcha-response']);
	 $responseData = json_decode($verifyResponse); 
	 if ($responseData->success) 
	   {$captcha = true;} 
  } 
  
  $suspect = (!$sameIp) || ($mtd < 1.5) || $spam || (!$captcha);
 	 
  $bcc = "michael.kahle@geographie.uni-freiburg.de";
  #$bcc = null;
  //$to = 'micha.kahle@gmail.com';
  $betreff = "WEBGEO";
  if ($postData['bug']) {
	$to = "michael.kahle@ub.uni-freiburg.de"; 
    $bcc = null;	
	$betreff .= " Fehlerreport: ".$postData['bug'];  
    logIntoFile($postData['bug'], 'bugs.txt', 1000, TRUE, TRUE);	
  } else {
    if ($postData['answer']) {
      $betreff .= " Anfrage";
    } else {
      $betreff .= " Kommentar";    
    }
  }	
  
  if ($suspect) {
    $to = "michael.kahle@ub.uni-freiburg.de";
	#$to = null;
	$bcc = null;
    $betreff .= " (?)";
    logIntoFile($ip1, 'spam_ips.txt', 1000);
  } else {
    logIntoFile($ip1, 'no_spam_ips.txt', 1000); 
  }
  $name = htmlspecialchars($postData['name']);
  $mail = htmlspecialchars($postData['email']);
  if (filter_var($mail, FILTER_VALIDATE_EMAIL) && $captcha)
  { 
    $from = "From: ".$name." <".$mail.">\r\n";
    $from .= "Reply-To: ".$mail."\r\n";
    if (!$suspect) {
      $from .= 'Cc: '.$mail."\r\n";
    }
	if($bcc) {
      $from .= 'Bcc: '.$bcc."\r\n";
	}
    $from .= "Content-Type: text/html\r\n";
    $text ="<a href='http://www.webgeo.de'><img src='http://www.webgeo.de/common/img/webgeo_header.gif?ip=".$ip1."'></a><br/>\r\n";
    $text .= "<b>".$name." schrieb:</b><br/>\r\n";  
    $text .= str_replace("\n",'<br/>', htmlspecialchars($postData['message']));
    $text .= "<br/>\r\n<span style='font-size:1px'>--------------------(".$ip1.")--------------------</span><br/>\r\n";
    if ('185.130.184.213' == $ip1) {
      sleep(5);  
      //mail($to, $betreff, $text, $from);
    } else {
		if($to) {
           mail($to, $betreff, $text, $from);
		}
    }
  }
  else
  {
    $errorInMail = true; 
  }
  return $errorInMail;
}

function printMailForm($errorTerm=null)
{
  $ip = $_SERVER['REMOTE_ADDR'];
  $mt = microtime(TRUE);

  echo "<br/>";    
  echo " <h6>Kontaktformular</h6>\n";
  if($errorTerm) {
	$errorArray = explode(';', $errorTerm);
    $errorModule = $errorArray[0];
    $errorPage = $errorArray[1];   
    echo " <p class='small'>Bitte überprüfen sie, ob der Fehler <a href='https://github.com/ruffle-rs/ruffle/issues?q=is%3Aissue+is%3Aopen+".$errorModule."%2F%23".$errorPage."' target='github'>schon berichtet</a> wurde.</p>\n";
    echo " <p class='small'>Für den Fehlerhinweis, Verbesserungsvorschlag, Erweiterungswunsch und Ähnliches benutzen sie bitte folgendes Formular:</p>\n"; 	  
  } else {
    echo " <p class='small'>Für Kommentare, Anfragen, Fehlerhinweise, Verbesserungsvorschläge, Erweiterungswünsche und Ähnliches benutzen sie bitte folgendes Formular:</p>\n"; 	  
  }
  if ($_POST) {
    echo "<p><b>Fehler beim Senden, bitte eMail-Adresse und Captcha überprüfen!</b></p>";
  }

  //echo "<form action='http://www.webgeo.de/kontakt/' id='mailform' name='mailform' enctype='multipart/form-data' method='post'>\n";
  echo "<form class='small' action='http://www.webgeo.de/kontakt/' id='mailform' name='mailform' enctype='multipart/form-data' method='post'>\n";
  //echo  "";
  echo " <div style='display:none;'><input autocomplete='false' type='hidden' name='ip' value='".$ip."'></div>";
  echo " <div style='display:none;'><input autocomplete='false' type='hidden' name='mt' value='".$mt."'></div>"; 
  echo " <div style='display:none;'><input autocomplete='false' type='hidden' name='bug' value='".$errorTerm."'></div>";
  echo " <div style='display:none;'><input autocomplete='false' type='hidden' name='browser' value='".$_SERVER['HTTP_USER_AGENT']."'></div>";  
  
  echo " <input style='width: 100%;' type='text' name='name'  value='".htmlspecialchars($_POST['name'])."' placeholder='Ihr Name'><br/>\n";
  echo " <input style='width: 100%;' type='text' name='email' value='".htmlspecialchars($_POST['email'])."' placeholder='Ihre E-Mail-Adresse'><br/>\n";
  if($errorTerm) {
	$ruffleVersion = strftime ("nightly %Y-%m-%d",  filemtime('common/vendor/ruffle/ruffle.js')); 
    $errorArray = explode(';', $errorTerm);
    $errorModule = $errorArray[0];
    $errorPage = $errorArray[1]; 	
	$errorText = "Fehlerumgebung: \n"
	           //. "=============== \n"
	           . " * Webgeo-Modul: ".$errorModule."\n"
			   . " * Webgeo-Seite: #".$errorPage."\n"
			   . " * Webgeo-Link: http://www.webgeo.de/".$errorModule."#".$errorPage."\n"
               . " * Ruffle-Version: ".$ruffleVersion."\n"			   
               . " * Browser-Version: ".$_SERVER['HTTP_USER_AGENT']."\n\n"	
               . "Fehlerbeschreibung: \n"
			   //. "=================== \n"
			   . " (...Hier detailierte Fehlerbeschreibung (bitte für jede Seite einzeln!) einfügen...)"; 
	// $errorText = "Im Modul: ".$errorTerm." tritt \n in der Ruffle Version ".$ruffleVersion." \n folgender Fehler auf: \n";   
    echo " <textarea style='width: 100%;' name='message' rows='10' placeholder='Ihre Nachricht'>".htmlspecialchars($errorText)."</textarea><br/>\n";
  } else {
    echo " <textarea style='width: 100%;' name='message' rows='10' placeholder='Ihre Nachricht'>".htmlspecialchars($_POST['message'])."</textarea><br/>\n";  
  }
  echo " <input c2lass='form-check-input' type='checkbox' value='1' name='answer' >\n";  
  echo " <label>Bitte um Antwort</label> \n";
  echo " <div class='g-recaptcha' data-sitekey='6LcUOq4bAAAAAAh5u7H_LmNp4C4NNb0eKymQb2iX'></div><br/> \n";
  echo " <input class='float-right' type='submit' name='mail' value='Senden' >\n";  
  echo " <br/>\n";  
  echo " </form>  \n";
  echo " <br/>\n"; 
  return TRUE;  
}

function getUrlAsText($url, $force = false, $timeFactor = 1)
{
  if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2*$timeFactor);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4*$timeFactor);
    $result = curl_exec($ch);
    $errorNum = curl_errno($ch);
    $errorTxt = curl_error($ch);
    curl_close($ch);
    if (($timeFactor < 100) && (CURLE_OPERATION_TIMEDOUT == $errorNum)) {
      $result = getUrlAsText($url, $force, 2*$timeFactor);
    } elseif (CURLE_OK != $errorNum) {
      $result = '{"errorNo":'.$errorNum.', "errorText":"'.$errorTxt.'"}';
    }                
   } else {
    $result = file_get_contents($url);
   } 
  return $result;
}

function getUrlAsJson($url, $force = false) {
  $text = getUrlAsText($url, $force);
  $json = json_decode($text, true);
  return $json;
}    

function getLocationByIp($ip = null, $inclGeo = false)
{
  $location = NULL;
  if(!is_string($ip) || (0==strlen($ip))) {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  if(is_null($ip)) {
    return NULL;
  }  
  if (is_string($ip)) {
    //   Needs replacement "# You will be required to create an account at https://ipstack.com and obtain an API access key.  
    /*
    $tags = getUrlAsJson('http://freegeoip.net/json/' . $ip);
    if (is_array($tags) && array_key_exists('city', $tags) && array_key_exists('country_name', $tags) && $tags['city'] != '') {
      $location = $tags['city'] . ', ' . $tags['country_name'];
      
    }
    */
    if (is_null($location)) {
      //$tags = getUrlAsJson('http://ipinfo.io/' . $ip.'/geo');
      $tags = getUrlAsJson('http://ipinfo.io/' . $ip.'/json');
      if (is_array($tags) && array_key_exists('city', $tags) && array_key_exists('country', $tags) && $tags['city'] != '') {
        $location = $tags['city'] . ', ' . $tags['country'];
        if ($inclGeo) {
        $location .= ';'.$tags['region'].';'.$tags['loc'].';'.$ip.';'.$tags['org'];
        }
      }
    }
  }
  return $location;
}

function isBot($useragent){
    $regex = "/crawler|bot|compatible|spider|larbin|python-requests|ABACHOBot|80legs|AddSugarSpiderBot|AnyApexBot|Baidu|B-l-i-t-z-B-O-T|BecomeBot|BillyBobBot|Bimbot|Arachmo|Accoona-AI-Agent|searchme\.com|Cerberian Drtrs|DataparkSearch|Covario-IDS|EPiServer|findlinks|holmes|htdig|HTTPing|ia_archiver|ichiro|igdeSpyder|Java|L\.webis|LinkWalker|lwp-trivial|mabontland|Google|masscan|Mnogosearch|mogimogi|MVAClient|NetResearchServer|NewsGator|NG-Search|Nymesis|oegp|Pompos|pdrlabs|PycURL|Qseero|SBIder|SBIder|ScoutJet|Scrubby|SearchSight|semanticdiscovery|silk|Snappy|Sqworm|StackRambler|Ask Jeeves\/Teoma|truwoGPS|voyager|VYU2|^updated|TinEye|webcollage|Yahoo|yoogliFetchAgent|^Zao/i";
    if(preg_match($regex, $useragent) !== 0)
    {
        return true;
    }
    return false;
}

function countLogFile($check, $fileName='webgeo_log.txt', $maxEntries=100, $useCounter=FALSE)
{
  $total = 0.0;
  $logEntries = inqLogFile($fileName, $maxEntries, FALSE); 
  foreach($logEntries as $logLine)
  { 
    $value = 1.0;
    $entry = $logLine;
    if ($useCounter) {
      $value = (int)array_pop(explode(':', $logLine)); 
      $entry = removeCounter($logLine); 
    }  
    if ($entry == $check) {
      $total = $total + $value;
    }        
  }
  return $total;
}

function isSpam($ip = '185.130.184.230', $msg = '') {
   $suspect = 0.0; 
   $spam0 = isSpam0($ip, $msg);  //internal message analyze
   $suspect += $spam0['suspect'];
   $spam1 = isSpam1($ip);        //internal counting 
   $suspect += $spam1['suspect'];   
   $spam2 = isSpam2($ip);        //external service
   $suspect += $spam2['suspect'];
   $spam = ($suspect > 1.5);
   if(($spam0['spam'] or $spam1['spam']) and !$spam2['spam']) {
      reportSpam($ip);
   }
   return ($spam0['spam'] or $spam1['spam'] or $spam2['spam']);   
}

function isSpam0($ip = '185.130.184.218', $msg = '') {
   $suspect = 0.0;
   $msg = strtolower($msg);
   $texts = ['sex' => 0.89, 'saftige frau' => 0.8, 'remokna' => 0.5, 'ремонт окон' => 0.5, 'frau' => 0.1,
            'hot women' => 0.8, 'http' => 0.4, 'invest'=> 0.2, '$'=>0.1, 'howtoinvest' => 0.7,
            'pay' => 0.1, '$1000' => 0.6, '$6000' => 0.6, 'bestinvestsystem' => 0.8, 'woman' => 0.2, 
            'sexywomaninyourcity' => 0.9, '$450 per hour' => 0.6,  'juicy women' => 0.8, 'per hour' => 0.2,
            'bestsexygirlsinyourcity' => 0.8, 'waterloo-collection.ru' => 0.4, 'Георгиевский крест' => 0.2,
            'alfredmus' => 0.5, 'sip5.ru' => 0.6, 'тротуарной' => 0.2, 'fineoffer' => 0.1, 'per month' => 0.2, 
            'hey complimentary' => 0.2, 'top cryptocurrencies' => 0.6, 'bestforex' => 0.3, '$100,000 per month' => 0.7,
            'forex signals' => 0.6, 'how To make $' => 0.7, 'how To make' => 0.1, 'cryptocurrency investment' => 0.7,
            'geld von zu haus' => 0.6, 'geld' => 0.3, 'perkele.ovh' => 0.4, 'business person' => 0.3,
            'marketing efforts' => 0.3, 'insane growths' => 0.3, 'conversion revenue' => 0.3, 'talkwithlead' => 0.4,
            'talkwithcustomer.com'=>0.5, 'days free trial' => 0.4, 'girl for the night' => 0.3, 'fabrikverkauf' => 0.4,
            '% günstiger' => 0.3, 'günstiger' => 0.1, 'hochwertige waren' => 0.4, 'folmax.de' => 0.3, 'zakon-nn.ru' => 0.6,
            'foremostoffers' => 0.5, 'servicerubin.ru' => 0.5, 'adultdating' => 0.5, 'urgroup.online' => 0.4, 
            'монтаж рекламы' => 0.5, 'bestsexygirls' => 0.7, 'Sexy Girls' => 0.5, 'trader makes $' => 0.4,
            'money-earning' => 0.5, 'make-money' => 0.5, 'How To Make $' => 0.5, 'Продают' => 0.3, 'won' => 0.2,
            'немыслемым ценам' => 0.4, 'подделки' => 0.2, 'Win iPhone' => 0.4, 'winiphone' => 0.4, 'iPhone' => 0.2,
            'cryptocurrency' => 0.4, 'fireontherim.com' => 0.2, 'Investment Guide' => 0.6, 'geld' => 0.2, '$' => 0.1,
            'Jameswox' => 0.3, 'invest $' => 0.3, 'Make $' => 0.3, 'Fast Money' => 0.3, 'more money' => 0.3,
            'qualify click' => 0.3, 'Hey Good tidings' => 0.3, 'Extra Money' => 0.4, 'money' => 0.2, 'adult' => 0.2,
            'bestadultdating' => 0.5, 'girl for the night' => 0.8, 'daymusic.org' => 0.4, 'per day' => 0.2, 
            'girl' => 0.1, 'Facebook' => 0.3, 'Twitter' => 0.3, 'Instagram' => 0.3, 'creapublicidadonline' => 0.8,
            '% discount' => 0.7, 'commercial offer'	=> 0.4, 'first purchase' => 0.3, 'alychidesign' => 0.3, 'promoted' => 0.3,
            'increase profits' => 0.7, 'profits' => 0.1, 'business' => 0.1, 'retargeting' => 0.1, 'remarketing' => 0.15, 
            'marketing' => 0.1 			
            
            ];
   foreach($texts as $text=>$value) {
     if(false !== strpos($msg,strtolower($text))) {
       $suspect += $value;  
     }
   }
   return ['spam' => ($suspect > 0.9), 'suspect' => $suspect];
   //return ($suspect > 0.9);
}

function isSpam1($ip = '185.130.184.218', $msg = '') {
   $count1 = countLogFile($ip, 'spam_ips.txt', $maxEntries=100);
   $count2 = countLogFile($ip, 'no_spam_ips.txt', $maxEntries=100);
   $count3 = 0.0;
   $agent = $_SERVER['HTTP_USER_AGENT'];
   if(isBot($agent)) {
     $count3 = 1.0;  
   }
   $count = $count1 * 1 + $count2 * 1.5 + $count3;
   return ['spam' => (count >= 5.0), 'suspect' => $count/5.0];
   //return ($count >= 5.0);
}

function isSpam2($ip = '185.130.184.218', $msg = '') {
  $apiKey = 'bdZLR7VmipCSQHbzLpAFqNTPpqCADAa3xf7ggTNq';  
  $url = "https://www.abuseipdb.com/check/".$ip."/json?key=".$apiKey."&days=30";
  $tags = getUrlAsJson($url);
  $counting = 0.5;
  $abuse = 0;
  foreach($tags as $tag) {
    if(array_key_exists('abuseConfidenceScore', $tag)) {
      $abuse += (int)$tag['abuseConfidenceScore'];
      $counting += 1.0;         
    }
  }
  $suspect = $abuse/$counting;
  return ['spam' => ($suspect > 70.0), 'suspect' => $suspect/70.0];
  //return ($abuse/$counting > 70.0);
}

function reportSpam($ip = '185.130.184.218') {
  //if(!isSpam2($ip)['spam']) {
    $apiKey = 'bdZLR7VmipCSQHbzLpAFqNTPpqCADAa3xf7ggTNq';  
    $url = "https://www.abuseipdb.com/report/json?key=".$apiKey."&category=11&comment=webgeo.de+email+contact+missuse&ip=".$ip;
    $tags = getUrlAsJson($url); 
    logIntoFile($ip, 'report_spam_ips.txt', 1000);
  //}      
}


?>
