//

var vueSolar = new Vue({
  el: '#solar2',
  data: {
    deltaList: [-23.5, 0, 23.5],
    latList: [0, 23.5, 50, 66.5, 90],
    delta: 0,
    latitude: 50,
	selSeason: null,
	selLatitude: '',
  },
  methods: { 
    randomTrail() {
      var deltaN = Math.floor(Math.random() * this.deltaList.length);
      this.delta = this.deltaList[deltaN];
      var latN = Math.floor(Math.random() * this.latList.length);
      this.latitude = this.latList[latN];
      drawSunRail(this.delta, this.latitude);
      this.selSeason = null;
	  this.selLatitude = '';
	},
	showTips() {
	},
	showSolution() {
		
	},

  },
  mounted () {
     this.randomTrail();
  }

}) 
