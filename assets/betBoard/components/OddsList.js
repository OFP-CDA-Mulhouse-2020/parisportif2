import React, {Component} from 'react';

class OddsList extends Component {

    selectedEvent = (props) => {
    this.props.selectedBet(props);
}

render() {
    return (
        this.props.oddsListData.listOfOdds.map( (row, index )  => (
            <Odds row={[this.props.oddsListData.id, index, row]}  key={index}
                  selectedEvent={this.selectedEvent}
                  updateCart = {this.props.updateCart}

            />

            ))
    );
    }
}

class Odds extends Component{
    constructor(props) {
        super(props);
        this.state = {
            selected: false,
            color:"btn btn-secondary btn-lg",
            buttonData: [],
            loading: true
        };
    }

    addOddsToCart = (props) => {

        this.props.updateCart();
        const url = `/api/cart/add/` + props[0] + `/` + props[1];
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);

                return response.json();
            })
            .then(json => {
                this.setState({buttonData: json.itemId, loading: true});
                console.log(this.state.buttonData);

            });
    }



    removeOddsFromCart = (props) => {

        this.props.updateCart();
         const url = `/api/cart/remove/` + this.state.buttonData;
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            });
    }


    selectedOdds = (props) => {
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
                {this.props.row[2][1]}
            </button>
        </td>
    );

    }
}

export default OddsList;
