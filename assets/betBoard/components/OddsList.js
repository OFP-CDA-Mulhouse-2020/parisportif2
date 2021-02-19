import React, {Component} from 'react';

class OddsList extends Component {

    render() {
        return (
            this.props.oddsListData[0].listOfOdds.map((row, index) => (
                <Odds row={[this.props.oddsListData[0].id, index, row, this.props.oddsListData[1]]} key={index}
                      addOddsToCart={this.props.addOddsToCart}
                      removeOddsFromBetBoard={this.props.removeOddsFromBetBoard}
                />
            ))
        );
    }
}

class Odds extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selected: false,
            color: "btn btn-secondary btn-lg",
            itemId: null,
            betId: this.props.row[0],
            oddsIndex: this.props.row[1],
            loading: false,
        };
    }

    componentDidMount() {
      //  console.log('didmount', this.props, this.state)
        if(!this.state.loading){
            this.displayButtonGreen();

        }
    }


    displayButtonGreen =() => {
        this.props.row[3].forEach(item => {
        console.log(item)
            if (this.props.row[0] === item[0] && this.props.row[1] === item[1]) {
                console.log('button green',this.props.row ,item )
                this.setState({selected: true, color: "btn btn-success btn-lg active", itemId: item[2], loading:true})
            }
            else {
                this.setState({selected: false, color: "btn btn-secondary btn-lg", itemId: null, loading:true})
                console.log('else', this.state)
            }
        })
    }

    selectedOdds = () => {
        if (!this.state.selected) {
            this.props.addOddsToCart([this.state.betId, this.state.oddsIndex]);
        } else {
            this.props.removeOddsFromBetBoard(this.state.itemId);
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {

        console.log('updateOddds', this.props, this.state)
        if (
            this.props.row[0] !== this.state.betId || this.props.row[1] !== this.state.oddsIndex) {
                console.log('yes')
           // this.setState({loading : false})
         //   this.displayButtonGreen()
        }

    }


    render() {
        if(this.state.loading){
            return (
                <td className="text-center">
                    <button className={this.state.color} role="button" aria-pressed="true"
                            onClick={this.selectedOdds}>
                        {this.props.row[2][1]}
                    </button>
                </td>
            );


        }else{
            return (
                <td className="text-center">

                </td>
            )
        }


    }
}

export default OddsList;
