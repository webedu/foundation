{
	
var sunRailFront = null;	
var sunRailBack = null;	
var sunFront = null;
var sunBack = null;
var angleLine = null;
var angleText = null;
var angleArc = null;
var dayLightText = null;
var mSkew = null;
	
function initSun() {	
//var elem = document.getElementById('page02').shadowRoot.getElementById("solar1");
var elem = document.getElementById("solar1");
if(elem) {
//if(1==1) {	
var s = Snap(elem);
var group = s.group();

var horizon = s.ellipse(200,200,150,60);
horizon.attr({
    fill: "#55da55",
    stroke: "#000",
    strokeWidth: 2
});

var horLine = s.line(52,213,348,187);
horLine.attr({stroke: "#000"});
var verLine = s.line(200,140,200,260);
verLine.attr({stroke: "#000"});
group.add(horizon,horLine,verLine);

var eastText = s.text(195,280, 'O');
var westText = s.text(195,130, 'W');
var southText = s.text(35,218, 'S');
var northText = s.text(355,192, 'N');

group.add(eastText,westText,southText,northText);

var ego = s.circle(200,200,5);
ego.attr({
    stroke: "#F00",
	fill: "r()#F55-#D33",
    strokeWidth: 1
});

mSkew = Snap.matrix().skewX(-23).translate(87,0).skewY(5).translate(0,-17);
group.transform(mSkew.toTransformString());

sunFront = s.circle(200,200,10).insertAfter(group);
sunFront.attr({
    stroke: "#FF0",
	fill: "r()#FD3-#CB0",
    strokeWidth: 1
});

sunRailFront = s.path("M45,213 A155,65 0 0 0 355,187").insertAfter(group)
sunRailFront.attr({
    stroke: "#AA5",
	fill: 'none',
    strokeWidth: 2,
	strokeDasharray: '7,3'
});

sunRailBack = s.path("M45,213 A155,65 0 1 1 355,187").insertBefore(group)
sunRailBack.attr({
    stroke: "#AA5",
	fill: 'none',
    strokeWidth: 2,
	strokeDasharray: '7,3'
});

sunBack = s.circle(200,200,10).insertBefore(group);
sunBack.attr({
    stroke: "#FF0",
	fill: "r()#FD3-#CB0",
    strokeWidth: 1
});

angleArc = s.path("M55,200 A155,155 0 0 1 50,200");
angleArc.attr({
    stroke: "#000",
	fill: 'none',
    strokeWidth: 1
});

angleLine = s.line(200,200,100,200);
angleLine.attr({stroke: "#000"});
angleText = s.text(200,200,'°');
dayLightText = s.text(50,380,'Tageslänge:');
}
}

initSun();

/*
// problems with perspektive
var sunriseLine = s.line(200,200,200,140);
sunriseLine.attr({stroke: "#C94",
strokeWidth: 2,
strokeDasharray: '3,3'
});
var sunsetLine = s.line(200,200,200,260);
sunsetLine.attr({stroke: "#C94",
strokeWidth: 2,
strokeDasharray: '3,3'
});
group.add(sunriseLine,sunsetLine);
*/

var dayAngle = 0.0;
function drawSunRail(delta, latitude) {
if(sunRailFront) {
 var shift = -150*Math.sin(delta*Math.PI/180);
 var rot = 90-latitude;

 var mShift= Snap.matrix().translate(00,shift);
 var mRotate= Snap.matrix().rotate(rot, 200,200);
//var mSkew= Snap.matrix().skewX(-23).translate(87,0).skewY(5).translate(0,-17); 
 var mCopy = mSkew.clone();
 var mNew = mCopy.multLeft(mShift.multLeft(mRotate));
 //var mScale = Snap.matrix().scale(0.4,1,200,200);
 //mScale.multLeft(mNew);

 sunRailFront.transform(mNew.toTransformString());
 sunRailBack.transform(mNew.toTransformString());

 var tr = Snap.path.map('M45,213', sunRailBack.transform().globalMatrix );
 angleLine.attr({x2: tr[0][1], y2: tr[0][2]});
 var anglePath = "M55,200 A155,155 0 0 1 "+tr[0][1].toString()+','+tr[0][2].toString();
 angleArc.attr({path: anglePath});

 var aScale = Snap.matrix().scale(0.3,0.3,200,200);
 angleArc.transform(aScale.toTransformString());
 var le = angleArc.getTotalLength()/2.0;
 var angleMid = angleArc.getPointAtLength(le);
 var pointMid =  'M'+angleMid.x.toString()+','+angleMid.y.toString();
 var pointText = Snap.path.map(pointMid, angleArc.transform().globalMatrix );

 var angleValue = Math.round(10*(90-latitude+delta))/10;;
 angleText.attr({x: pointText[0][1]-40, y: pointText[0][2], text: angleValue.toString()+'°'});

 var dayCosinus = -1.0*Math.tan(latitude*Math.PI/180)*Math.tan(delta*Math.PI/180);
 if (dayCosinus >1.0) {
   dayAngle = 0.0;
 } else if (dayCosinus < -1.0) {
   dayAngle = 360.0; 
 } else {
   dayAngle = 180*2.0*Math.acos(dayCosinus)/Math.PI;
 }
 var dayHours = Math.round(1000*24.0*dayAngle/360.0)/1000;
 var hours = Math.floor(dayHours);
 var minutes = Math.round(60*(dayHours-hours));
 var dayText = 'Tageslänge: '+hours.toString()+':';
 if(minutes<10) { dayText += '0'; }
 dayText += minutes.toString()+' Stunden';
 dayLightText.attr({text: dayText});
 
 
 var weiteSin = Math.sin(delta*Math.PI/180)/Math.cos(latitude*Math.PI/180);
  angleWeite = 0.0;
  if (weiteSin >1.0) {
   angleWeite = 90.0;
 } else if (weiteSin < -1.0) {
   angleWeite =-90.0; 
 } else {
   angleWeite = 180*2.0*Math.asin(weiteSin)/Math.PI;
 }
 
 /*
 // Problems with perspektive
 var lh = sunRailFront.getTotalLength();
 var lsr = lh*(90+angleWeite)/180.0;
 var lss = lh*(90+angleWeite)/180.0;
 var psr = sunRailFront.getPointAtLength(lsr);
 var pss = sunRailBack.getPointAtLength(lss); 
 sunriseLine.attr({x2: psr.x, y2: psr.y});
 sunsetLine.attr({x2: pss.x, y2: pss.y});
 */
 
 var x=2;

}
}
drawSunRail(0, 45);

function moveSun(fraction) {
 //var day = true;	
 //var dayFraction = dayAngle/360.0;
if(sunRailFront) { 
 if (fraction < 0.5) {
  //day = (fraction < dayFraction/2.0);	 
  var le = 2.0*fraction*sunRailBack.getTotalLength();
  var ps = sunRailBack.getPointAtLength(le);
  var pa = 'M'+ps.x.toString()+','+ps.y.toString();
  var tr = Snap.path.map( pa, sunRailBack.transform().globalMatrix );
  sunBack.attr({cx: tr[0][1], cy: tr[0][2]});
  sunFront.attr({cx: -100, cy: -100});
 } else {
  //day = (fraction > (1.0 - dayFraction/2.0));	 
  var le = 2.0*(1.0-fraction)*sunRailFront.getTotalLength();
  var ps = sunRailFront.getPointAtLength(le);
  var pa = 'M'+ps.x.toString()+','+ps.y.toString();
  var tr = Snap.path.map( pa, sunRailFront.transform().globalMatrix );	
  sunFront.attr({cx: tr[0][1], cy: tr[0][2]});
  sunBack.attr({cx: -100, cy: -100});
 }
 /*
 if(day) {
	sunFront.attr({stroke: '#FF0'}); 
	sunBack.attr({stroke: '#FF0'});
 } else {
	sunFront.attr({stroke: '#55F'}); 
	sunBack.attr({stroke: '#55F'});
 }
 */
}
}

}

function doAnimation() {
  Snap.animate(0, 1, moveSun, 4000, mina.linear, doAnimation);
}
doAnimation();

