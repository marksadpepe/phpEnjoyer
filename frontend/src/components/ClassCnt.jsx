import React from "react";

class ClassCnt extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      hits: 0,
    };

    this.inc = this.inc.bind(this);
  }

  inc() {
    this.setState({hits: this.state.hits + 1});
  }

  render() {
    return ( 
      <div>
        <h1>Hits - {this.state.hits}</h1>
        <button onClick={this.inc} className="btn">Hit</button>
      </div>
    );
  }
}

export default ClassCnt;
