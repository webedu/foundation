function sliderChanged() {
	//var delta = 0.1*parseFloat(document.getElementById('page02').shadowRoot.getElementById("delta").value);
	//var latitude = 0.1*parseFloat(document.getElementById('page02').shadowRoot.getElementById("latitude").value);
	var delta = 0.1*parseFloat(document.getElementById("delta").value);
	var latitude = 0.1*parseFloat(document.getElementById("latitude").value);	
	console.log('drag');
	drawSunRail(delta, latitude);
};


/*
function initSliders() {
console.log('init');
document.getElementById("delta").oninput = function() {
    sliderChanged();
};
document.getElementById("latitude").oninput = function() {
    sliderChanged();
};

//
document.getElementById('page02').shadowRoot.getElementById("delta").oninput = function() {
    sliderChanged();
};
document.getElementById('page02').shadowRoot.getElementById("latitude").oninput = function() {
    sliderChanged();
};
//
sliderChanged();

}


document.onload = function(){initSliders(); };
*/