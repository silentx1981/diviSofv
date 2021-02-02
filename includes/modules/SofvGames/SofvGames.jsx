// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class SofvGames extends Component {

  static slug = 'sofv_sofv_games';

  render() {

    return (
        <div>
          {this.props.content()}
        </div>

    );
  }
}

export default SofvGames;
