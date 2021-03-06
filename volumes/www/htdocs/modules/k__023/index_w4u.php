<?php require('../common/php/pages.php'); 
 $f = collectPages(".");
 var_dump($f);

?>

<html>
 <head>
  <meta charset="utf-8">
  <title>Scheinbare Sonnenbahnen</title>
  <link rel="shortcut icon" type="image/x-icon" href="https://cdn.jsdelivr.net/gh/webedu/decoration@master/icons/favicon.ico" >
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/bootstrap/bootstrap.min.css" type="text/css"> 
 </head>
 <body>

<script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/vue/vue.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcomponentsjs/1.2.0/webcomponents-loader.js"></script>

<!--script src="https://cdn.jsdelivr.net/gh/webedu/npm@master/packages/c4u-main/dist/c4u-main.min.js" type="text/javascript"></script-->
<!--script src="https://cdn.jsdelivr.net/gh/webedu/npm@master/packages/w4u-main/dist/w4u-main.min.js" type="text/javascript"></script-->
<script src="../../common/js/w4u/c4u-main.min.js"></script>
<script src="../../common/js/w4u/w4u-main.min.js"></script>




 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/three/three.min.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/three/Projector.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/three/SVGRenderer.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/dot/doT.min.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/uevent/uevent.min.js" type="text/javascript"></script> 
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/promise-polyfill/Promise.min.js" type="text/javascript"></script> 
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/photo-sphere/photo-sphere-viewer.min.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/snapsvg/snap.svg-min.js" type="text/javascript"></script>

 <div class="container-fluid">
  <div class="row" style="margin: 8px;">
   <div class="col-md-1"></div>
    <div class="col-md-10"> 
	
  </div>
  <div class="col-md-1"></div> 
 </div> 
</div>

   <c4u-pages>
    <!--c4u-page><c4u-html url='./pages/00_intro.html'></c4u-html></c4u-page-->
    <!--c4u-page><c4u-html url='./pages/01_page.html'></c4u-html></c4u-page-->
    <c4u-page id="01_page"><?php include('./pages/01_page.html')?></c4u-page>
    <!--c4u-page><? // php include('./pages/02_page.html')?></c4u-page>
    <c4u-page><c4u-html url='./pages/03_page.html'></c4u-html></c4u-page-->



	
<footer class="footer" style="position: absolute; bottom: 0; width: 100%">
 <div class="container-fluid">
  <div class="row" style="margin: 8px;">
   <div class="col-md-1"></div>
    <div class="col-md-10"> 
     <span class="float-left"><c4u-page-backward>B</c4u-page-backward></span>
     <span class="float-right"><c4u-page-forward>F</c4u-page-forward></span>
  </div>
  <div class="col-md-1"></div> 
</footer>

 </div> 
</div>



</c4u-pages>

<script src="https://cdn.jsdelivr.net/gh/webedu/foundation@master/vendor/bootstrap/bootstrap.bundle.min.js" type="text/javascript"></script>
<!--script src="./pages/sun.js" type="text/javascript"></script-->
<!--script src="./pages/01.js" type="text/javascript"></script-->
<!--script src="./pages/02.js" type="text/javascript"></script-->
<!--script src="./pages/03.js" type="text/javascript"></script-->

<!--c4u-script url="pages/02.js" ></c4u-script>
<c4u-script url="pages/03.js" ></c4u-script-->

</body>

</html>
