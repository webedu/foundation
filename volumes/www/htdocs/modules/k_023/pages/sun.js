class Sun {
  constructor(elemId) {
    this.elem = document.getElementById(elemId);
    if(this.elem) {
      this.initSun();
      this.drawSunRail(0, 45);
      this.doAnimation();
    }
  }
  
  initSun() {
    this.dayAngle = 0.0;
    this.s = Snap(this.elem);
    this.group = this.s.group();
    var horizon = this.s.ellipse(200,200,150,60);
    horizon.attr({
      fill: "#55da55",
      stroke: "#000",
      strokeWidth: 2
    });
    
    var horLine = this.s.line(52,213,348,187);
    horLine.attr({stroke: "#000"});
    var verLine = this.s.line(200,140,200,260);
    verLine.attr({stroke: "#000"});
    this.group.add(horizon,horLine,verLine);

    var eastText = this.s.text(195,280, 'O');
    var westText = this.s.text(195,130, 'W');
    var southText = this.s.text(35,218, 'S');
    var northText = this.s.text(355,192, 'N');
    this.group.add(eastText,westText,southText,northText);

    this.ego = this.s.circle(200,200,5);
    this.ego.attr({
      stroke: "#F00",
      fill: "r()#F55-#D33",
      strokeWidth: 1
    });

    this.mSkew = Snap.matrix().skewX(-23).translate(87,0).skewY(5).translate(0,-17);
    this.group.transform(this.mSkew.toTransformString());

    this.sunFront = this.s.circle(200,200,10).insertAfter(this.group);
    this.sunFront.attr({
      stroke: "#FF0",
      fill: "r()#FD3-#CB0",
      strokeWidth: 1
    });

    this.sunRailFront = this.s.path("M45,213 A155,65 0 0 0 355,187").insertAfter(this.group)
    this.sunRailFront.attr({
      stroke: "#AA5",
      fill: 'none',
      strokeWidth: 2,
      strokeDasharray: '7,3'
    });

    this.sunRailBack = this.s.path("M45,213 A155,65 0 1 1 355,187").insertBefore(this.group)
    this.sunRailBack.attr({
      stroke: "#AA5",
      fill: 'none',
      strokeWidth: 2,
      strokeDasharray: '7,3'
    });

    this.sunBack = this.s.circle(200,200,10).insertBefore(this.group);
    this.sunBack.attr({
      stroke: "#FF0",
      fill: "r()#FD3-#CB0",
      strokeWidth: 1
    });

    this.angleArc = this.s.path("M55,200 A155,155 0 0 1 50,200");
    this.angleArc.attr({
      stroke: "#000",
      fill: 'none',
      strokeWidth: 1
    });

    this.angleLine = this.s.line(200,200,100,200);
    this.angleLine.attr({stroke: "#000"});
    this.angleText = this.s.text(200,200,'°');
    this.dayLightText = this.s.text(50,380,'Tageslänge:');
  }

  drawSunRail(delta, latitude) {
    if(this.sunRailFront) {
      var shift = -150*Math.sin(delta*Math.PI/180);
      var rot = 90-latitude;

      var mShift= Snap.matrix().translate(0,shift);
      var mRotate= Snap.matrix().rotate(rot, 200,200);
      //var mSkew= Snap.matrix().skewX(-23).translate(87,0).skewY(5).translate(0,-17); 
      var mCopy = this.mSkew.clone();
      var mNew = mCopy.multLeft(mShift.multLeft(mRotate));
      //var mScale = Snap.matrix().scale(0.4,1,200,200);
      //mScale.multLeft(mNew);

      this.sunRailFront.transform(mNew.toTransformString());
      this.sunRailBack.transform(mNew.toTransformString());

      var tr = Snap.path.map('M45,213', this.sunRailBack.transform().globalMatrix );
      this.angleLine.attr({x2: tr[0][1], y2: tr[0][2]});
      var anglePath = "M55,200 A155,155 0 0 1 "+tr[0][1].toString()+','+tr[0][2].toString();
      this.angleArc.attr({path: anglePath});

      var aScale = Snap.matrix().scale(0.3,0.3,200,200);
      this.angleArc.transform(aScale.toTransformString());
      var le = this.angleArc.getTotalLength()/2.0;
      var angleMid = this.angleArc.getPointAtLength(le);
      var pointMid =  'M'+angleMid.x.toString()+','+angleMid.y.toString();
      var pointText = Snap.path.map(pointMid, this.angleArc.transform().globalMatrix );

      var angleValue = Math.round(10*(90-latitude+delta))/10;;
      this.angleText.attr({x: pointText[0][1]-40, y: pointText[0][2], text: angleValue.toString()+'°'});

      var dayCosinus = -1.0*Math.tan(latitude*Math.PI/180)*Math.tan(delta*Math.PI/180);
      if (dayCosinus >1.0) {
        this.dayAngle = 0.0;
      } else if (dayCosinus < -1.0) {
        this.dayAngle = 360.0; 
      } else {
        this.dayAngle = 180*2.0*Math.acos(dayCosinus)/Math.PI;
      }
      var dayHours = Math.round(1000*24.0*this.dayAngle/360.0)/1000;
      var hours = Math.floor(dayHours);
      var minutes = Math.round(60*(dayHours-hours));
      var dayText = 'Tageslänge: '+hours.toString()+':';
      if(minutes<10) { dayText += '0'; }
      dayText += minutes.toString()+' Stunden';
      this.dayLightText.attr({text: dayText});
 
      var weiteSin = Math.sin(delta*Math.PI/180)/Math.cos(latitude*Math.PI/180);
      this.angleWeite = 0.0;
      if (weiteSin >1.0) {
        this.angleWeite = 90.0;
      } else if (weiteSin < -1.0) {
        this.angleWeite =-90.0; 
      } else {
        this.angleWeite = 180*2.0*Math.asin(weiteSin)/Math.PI;
      }

      // Problems with perspektive
      // var lh = sunRailFront.getTotalLength();
      // var lsr = lh*(90+angleWeite)/180.0;
      // var lss = lh*(90+angleWeite)/180.0;
      // var psr = sunRailFront.getPointAtLength(lsr);
      // var pss = sunRailBack.getPointAtLength(lss); 
      // sunriseLine.attr({x2: psr.x, y2: psr.y});
      // sunsetLine.attr({x2: pss.x, y2: pss.y});

    }
  }

  moveSun(fraction) {
    //var day = true;	
    //var dayFraction = dayAngle/360.0;
    if(this.sunRailFront) { 
      if (fraction < 0.5) {
      //day = (fraction < dayFraction/2.0);	 
        var le = 2.0*fraction*this.sunRailBack.getTotalLength();
        var ps = this.sunRailBack.getPointAtLength(le);
        var pa = 'M'+ps.x.toString()+','+ps.y.toString();
        var tr = Snap.path.map( pa, this.sunRailBack.transform().globalMatrix );
        this.sunBack.attr({cx: tr[0][1], cy: tr[0][2]});
        this.sunFront.attr({cx: -100, cy: -100});
      } else {
        //day = (fraction > (1.0 - dayFraction/2.0));	 
        var le = 2.0*(1.0-fraction)*this.sunRailFront.getTotalLength();
        var ps = this.sunRailFront.getPointAtLength(le);
        var pa = 'M'+ps.x.toString()+','+ps.y.toString();
        var tr = Snap.path.map( pa, this.sunRailFront.transform().globalMatrix );	
        this.sunFront.attr({cx: tr[0][1], cy: tr[0][2]});
        this.sunBack.attr({cx: -100, cy: -100});
      }

      // if(day) {
      //	sunFront.attr({stroke: '#FF0'}); 
      //	sunBack.attr({stroke: '#FF0'});
      // } else {
      //	sunFront.attr({stroke: '#55F'}); 
      //	sunBack.attr({stroke: '#55F'});
      // }

    }
  }

  doAnimation() {
    var self = this;
    Snap.animate(0, 1, function(val) { self.moveSun(val) ;}, 4000, mina.linear, function() {  self.doAnimation();});
  }

}





