<?php
function checkRedirect() {
 $stringTerm = $_GET['string'];
 if(is_string($stringTerm) and count(explode(';',$stringTerm)) >= 3) {
   $newModule = explode(';',$stringTerm)[1];
   if(is_string($newModule) and strlen($newModule) >= 3) {   
     header('Location: /'.$newModule);
   }
 }	
}
?>
