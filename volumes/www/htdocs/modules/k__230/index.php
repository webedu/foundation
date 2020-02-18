<?php require('../../common/php/pages.php'); 
 $pages = collectPages(".");
?>

<html>
 <head>
  <meta charset="utf-8">
  <title>Adiabatische Prozesse</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../common/icons/favicon.ico" >
  <link rel="stylesheet" href="../../common/vendor/bootstrap/bootstrap.min.css" type="text/css"  w4u-type="global"> 
  <!--link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" w4u-type="global"-->
  <link href="../../common/vendor/fonts/awesome.min.css" rel="stylesheet" type="text/css" w4u-type="global">
  <link rel="stylesheet" type="text/css" href="../../common/css/main.css" media="screen" w4u-type="global"/>  
 </head>
 <body>

<script src="../../common/vendor/vue/vue.min.js" type="text/javascript"></script>
<script src="../../common/vendor/vue/webcomponents-loader.js"></script>
<script src="../../common/js/w4u/c4u-main.min.js"></script>
<script src="../../common/js/w4u/w4u-main.min.js"></script>
<script src="../../common/vendor/jquery/jquery.min.js"></script>

 <div class="container-fluid">
<p style="font-size:1px;">&nbsp;</p>
<div class="row">
      <div class="col-md-1 col-sm-0">
        <img style="margin-bottom:10px; display:none" class="float-right" id="backward" src="/common/img/backward.png"> 
      </div>
       <div class="col-md-4 col-sm-5 menu">
       <h2>Adiabatische Prozesse</h2>
       </div>
      <div class="col-md-6 col-sm-6">
        <img style="margin-bottom:10px;" class="float-right" src="/common/img/webgeo_header.gif">
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
    <?php includePages($pages); ?>
	
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

<script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/bootstrap/bootstrap.bundle.min.js" type="text/javascript"></script>

<script src="../../common/js/pages.js"></script>
<?php javascriptPages($pages); ?>
</body>

</html>
