//

var vueSolar = new Vue({
  el: '#solar3a',
  data: {
    sun3: null,
    deltaList: [-23.5, 0, 23.5],
    latList: [0, 23.5, 50, 66.5, 90],
    delta: 0,
    season: '',
    latitude: 50,
    selSeason: null,
    selLatitude: '',
    solutionVisible: false,
    tipsVisible: false,
  },
  methods: { 
    randomTrail() {
      this.solutionVisible = false;
      this.tipsVisible = false;
      var deltaN = Math.floor(Math.random() * this.deltaList.length);
      this.delta = this.deltaList[deltaN];
      if(this.delta>5.0) {this.season = 'Sommersolstitium';}
      else if(this.delta<5.0) {this.season = 'Wintersolstitium';}
      else {this.season = 'Äquinoktien';}
      var latN = Math.floor(Math.random() * this.latList.length);
      this.latitude = this.latList[latN];
      this.sun3.drawSunRail(this.delta, this.latitude);
      this.selSeason = null;
      this.selLatitude = '';
    },
    showTips() {
      this.tipsVisible = true;
    },
    hideTips() {
      this.tipsVisible = false;
    },
    showSolution() {
      this.solutionVisible = true;
    },

  },
  mounted () {
     this.sun3 = new Sun("solar3b");
     this.randomTrail();
  }

}) 
