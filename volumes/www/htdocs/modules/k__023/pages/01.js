var paraElem = document.getElementById("panorama");
//var page = document.getElementById('01_page')
//var para = page.shadowRoot.getElementById("panorama")


/*
var viewer = new PhotoSphereViewer({
  container: paraElem, // para, //'panorama',
  panorama: './img/panorama360.png',
  time_anim: false,
  navbar: [],
  pano_data: {
      full_width: 1925,
      full_height: 963,
      cropped_width: 1925,
      cropped_height: 421,
      cropped_x: 0,
      cropped_y: 210
  }
});
*/


// https://pannellum.org/documentation/reference/#api-events

pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "./img/panorama360.png",
    "autoLoad": true,
    "showControls": false,
    "minPitch":-45, "maxPitch":45,
    //"avoidShowingBackground": true,
    "vaov": 90,
    "hfov": 50,
});

$('#panorama').css('width','100%');
//$(window).trigger('resize');
$('#panorama').trigger('resize');

