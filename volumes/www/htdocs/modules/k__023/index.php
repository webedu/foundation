<?php require('../../common/php/pages.php'); 
 $pages = collectPages(".");
?>

<html>
 <head>
  <meta charset="utf-8">
  <title>Scheinbare Sonnenbahnen</title>
  <link rel="shortcut icon" type="image/x-icon" href="https://cdn.jsdelivr.net/gh/webedu/decoration@master/icons/favicon.ico" >
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/bootstrap/bootstrap.min.css" type="text/css"  w4u-type="global"> 
  <link rel="stylesheet" type="text/css" href="../../common/css/main.css" media="screen"  w4u-type="global"/>  
 </head>
 <body>

<script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/vue/vue.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcomponentsjs/1.2.0/webcomponents-loader.js"></script>

<!--script src="https://cdn.jsdelivr.net/gh/webedu/npm@master/packages/c4u-main/dist/c4u-main.min.js" type="text/javascript"></script-->
<!--script src="https://cdn.jsdelivr.net/gh/webedu/npm@master/packages/w4u-main/dist/w4u-main.min.js" type="text/javascript"></script-->
<script src="../../common/js/w4u/c4u-main.min.js"></script>
<script src="../../common/js/w4u/w4u-main.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

 <div class="container-fluid">
<p style="font-size:1px;">&nbsp;</p>
<div class="row">
      <div class="col-md-1 col-sm-0">
        <img style="margin-bottom:10px; display:none" class="float-right" id="backward" src="/common/img/backward.png"> 
      </div>
       <div class="col-md-4 col-sm-5 menu">
       <h2>Scheinbare Sonnenbahnen</h2>
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
