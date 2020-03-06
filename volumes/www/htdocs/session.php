<?php 
session_start();
$_SESSION["flash"] = $_REQUEST["flash"];
setcookie('flash', $_REQUEST["flash"],  time()+31104000);
session_write_close();
?>