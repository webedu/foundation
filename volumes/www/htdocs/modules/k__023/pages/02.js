const sun2 = new Sun("solar2");

function sliderChanged() {
	//var delta = 0.1*parseFloat(document.getElementById('page02').shadowRoot.getElementById("delta").value);
	//var latitude = 0.1*parseFloat(document.getElementById('page02').shadowRoot.getElementById("latitude").value);
	var delta = 0.1*parseFloat(document.getElementById("delta2").value);
	var latitude = 0.1*parseFloat(document.getElementById("latitude2").value);	
	console.log('drag');
	sun2.drawSunRail(delta, latitude);
};

window.onload = function(){
 sliderChanged(); 
};




