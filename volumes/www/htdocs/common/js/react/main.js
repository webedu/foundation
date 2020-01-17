'use strict';
//import Token1 from "./src/components/Token1.js";

const e = React.createElement;

class WebGeo extends React.Component {
  constructor(props) {
    super(props);
    this.state = { liked: false };
  }

  render() {
    if (this.state.liked) {
        //return <Token1 title='Example title'/>;
        return 'You liked this.';
    }

    return e(
      'button',
      { onClick: () => this.setState({ liked: true }) },
      'Like'
    );
  }
}

const domContainer = document.querySelector('#webgeo_container');
ReactDOM.render(e(WebGeo), domContainer);