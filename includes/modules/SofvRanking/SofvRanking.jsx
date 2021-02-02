// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class SofvRanking extends Component {

  static slug = 'sofv_sofv_ranking';

  render() {
    const Content = this.props.content;

    return (
      <h1>x
        <Content/>
      </h1>
    );
  }
}

export default SofvRanking;
