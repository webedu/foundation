class Polygon {
  constructor(hoehe, breite) {
    this.hoehe = hoehe;
    this.breite = breite;
  }
  
  get flaeche() {
    return this.berechneFlaeche();
  }

  berechneFlaeche() {
    return this.hoehe * this.breite;
  }
}

const quadrat = new Polygon(10, 10);

console.log(quadrat.flaeche);



// https://developer.mozilla.org/de/docs/Web/JavaScript/Reference/Klassen


