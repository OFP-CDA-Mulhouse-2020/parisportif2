import React, {Component} from 'react';

class OddsList extends Component {

    selectedEvent = (props) => {
    this.props.selectedBet(props);
}

render() {
console.log(this.props.oddsListData)
    return (
        this.props.oddsListData.odds.map( (row, index )  => (
            <Odds row={[this.props.oddsListData.id, index, row]}  key={index} selectedEvent={this.selectedEvent} />

            ))
    );
    }
}

class Odds extends Component{
    constructor(props) {
        super(props);
        this.state = {
            selected: false,
            color:"btn btn-secondary btn-lg"
        };
    }

    addOddsToCart = (props) => {
        console.log('odds', props);
        //      this.setState({coords: coordinates, loading: true});

        // const url = `/api/cart/add/selectedBet?betId=` + this.state.coords.lat + `&selectedOdds=` + this.state.coords.lng;

        const url = `/api/cart/add/` + props[0] + `/` + props[1];

        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({marketData: json, loading: true});
            });
    }



    removeOddsFromCart = (props) => {
        console.log('odds', props);
        //      this.setState({coords: coordinates, loading: true});

        // const url = `/api/cart/add/selectedBet?betId=` + this.state.coords.lat + `&selectedOdds=` + this.state.coords.lng;
/*
        const url = `/api/cart/add/` + props[0] + `/` + props[1];

        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({marketData: json, loading: true});
            });*/
    }





    selectedOdds = (props) => {
        //this.props.selectedEvent([this.props.row[0],this.props.row[1]]);
        if(!this.state.selected){
            this.setState({selected: true, color: "btn btn-success btn-lg active"})
            this.addOddsToCart([this.props.row[0],this.props.row[1]]);
        }else{
            this.setState({selected: false, color: "btn btn-secondary btn-lg"})
            this.removeOddsFromCart([this.props.row[0],this.props.row[1]]);
        }
    }

    render() {
    return(
        <td  className="text-center">
            <button className={ this.state.color}  role="button" aria-pressed="true"
                   onClick={this.selectedOdds} >
                {this.props.row[2]}
            </button>
        </td>
    );

    }
}

export default OddsList;
