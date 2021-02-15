import React, {Component} from 'react';

class Filter extends Component {

  filterBySport = (e) => {
    this.props.handleChange(e.target.value)
  }

  render() {
  
    return (
        <form className="form">
          <div className="btn-group">
            <input type="button" value="Football" className="btn btn-outline-secondary"
                   aria-current="page"
                   onClick={this.filterBySport}
            />
            <input type="button" value="Basketball" className="btn btn-outline-secondary"
                   aria-current="page"
                   onClick={this.filterBySport}
            />
            <input type="button" value="Tennis" className="btn btn-outline-secondary"
                   onClick={this.filterBySport}
            />
            <input type="button" value="Rugby" className="btn btn-outline-secondary"
                   onClick={this.filterBySport}
            />
          </div>
        </form>
    );
  }

}

export default Filter;
