import React, {Component} from 'react';

class OddsList extends Component {

render() {
    return (
        this.props.oddsListData[0].listOfOdds.map( (row, index )  => (
            <Odds row={[this.props.oddsListData[0].id, index, row, this.props.oddsListData[1]]}  key={index}
                  addOddsToCart = {this.props.addOddsToCart}
                  removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}

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
            itemId: null,
            betId: this.props.row[0],
            oddsIndex: this.props.row[1],
            loading: true,
        };
    }

    componentDidMount() {
        console.log(this.props)
        this.props.row[3].forEach(item => {
            if(this.props.row[0] === item[0] && this.props.row[1] === item[1]){
                this.setState({selected: true, color: "btn btn-success btn-lg active", itemId: item[2]})
            }
        })
    }

    selectedOdds = () => {
        if(!this.state.selected){
            this.setState({selected: true, color: "btn btn-success btn-lg active"})
            this.props.addOddsToCart([this.state.betId,this.state.oddsIndex]);
        }else{
            this.setState({selected: false, color: "btn btn-secondary btn-lg"})
            this.props.removeOddsFromBetBoard(this.state.itemId);
            }
    }

    render() {

    return(
        <td  className="text-center">
            <button className={ this.state.color}  role="button" aria-pressed="true"
                   onClick={this.selectedOdds}  >
                {this.props.row[2][1]}
            </button>
        </td>
            );

    }
}

export default OddsList;
