<?php require('../../common/php/flashes.php'); 
 $pages = collectFlashes(".", "contents");
?>

 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" --> 
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- base href="http://www.webgeo.de" -->
  <title><?php echo $metadata['title']['data']; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="../../common/icons/favicon.ico" >
  <link rel="stylesheet" href="../../common/vendor/bootstrap/bootstrap.min.css" type="text/css"  w4u-type="global"> 
  <link rel="stylesheet" type="text/css" href="../../common/css/main.css" media="screen"  w4u-type="global"/>  
 </head>
 <body>

<!--script src="../../common/vendor/vue/vue.min.js"></script>
<script src="../../common/js/w4u/c4u-main.min.js"></script>
<script src="../../common/js/w4u/w4u-main.min.js"></script-->

<!-- Include supporting javascript files -->
<script type="text/javascript">
	//<!--
	glossary_url	= "<?php echo $xmlvars["glossary_url"]; ?>";
	glossary_form	= "<?php echo $xmlvars["glossary_form"]; ?>";
	webgeo_home		= "<?php echo $xmlvars["webgeo_home"]; ?>";
	//-->
</script>
<script type="text/javascript" src="frame/js/menufunctions.js"></script>
<script type="text/javascript" src="frame/js/misc.js"></script>

<script src="../../common/vendor/jquery/jquery.min.js"></script>
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->

<script type="text/javascript">
    window.RufflePlayer = window.RufflePlayer || {};
    // Update the paths below to the directory containing the Ruffle files on your web server.
    window.RufflePlayer.config = {
      "public_path": "/common/vendor/ruffle/",
      "polyfills": ["static-content", "plugin-detect", "dynamic-content"]
    };
</script>
<script src="/common/vendor/ruffle/ruffle.js" type="text/javascript"></script>

 <div class="container-fluid">
<p style="font-size:1px;">&nbsp;</p>
<div class="row">
      <div class="col-md-1 col-sm-0">
        <img style="margin-bottom:10px; display:none" class="float-right" id="backward" src="/common/img/backward.png"> 
      </div>
       <div class="col-md-4 col-sm-5 menu">
       <h2><?php echo $metadata['title']['data']; ?></h2>
       </div>
      <div class="col-md-6 col-sm-6">
        <a href="/" onclick="return confirm('Modul verlassen?')"><img style="margin-bottom:10px;" class="float-right" src="/common/img/webgeo_header.gif"></a>
      </div>
      <div class="col-md-1 col-sm-1">
        <img style="margin-bottom:10px;" class="float-left" id="forward" src="/common/img/forward.png">    
      </div>
    </div>


  <div class="row" style="margin: 8px;">
   <div class="col-md-1"></div>
    <div class="col-md-10"> 
	
  </div>
  <div class="col-md-1"></div> 
 </div> 
</div>

   <div id="pages">
    <?php includeFlashes($pages); ?>
	
<footer class="footer" style="position: absolute; bottom: 0; width: 100%">
 <div class="container-fluid">
  <div class="row" style="margin: 8px;">
   <div class="col-md-1"></div>
   <div class="col-md-10"> 
   </div>
  <div class="col-md-1"></div> 
</footer>

 </div> 
</div>



</div>

<script src="../../common/vendor/bootstrap/bootstrap.bundle.min.js" type="text/javascript"></script>

<script src="../../common/js/pages.js"></script>
<?php javascriptFlashes($pages); ?>
</body>


