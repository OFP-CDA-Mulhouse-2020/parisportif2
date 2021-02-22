import React, {Component} from 'react';

class OddsList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
        };
    }

    componentDidMount() {
        if (this.props) {
            this.setState({loading:true});
            console.log("componentDidMount",this.props)
        }
    }

    render() {
        console.log("OddsList", this.props)
        if (this.state.loading) {

            return (
                this.props.oddsListData[0].listOfOdds.map((row, index) => (
                    <Odds row={[this.props.oddsListData[0].id, index, row, this.props.oddsListData[1]]} key={index}
                          addOddsToCart={this.props.addOddsToCart}
                          removeOddsFromBetBoard={this.props.removeOddsFromBetBoard}
                    />
                ))
            );

        } else {
            return (
                <td className="text-center">

                </td>
            )
        }

    }
}

class Odds extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selected: false,
            buttonColor: "btn-custom",
            iconDisplay: "hide fa fa",
            oddsDisplay: null,
            textDisplay: "hide",
            text: "",
            itemId: null,
            betId: this.props.row[0],
            oddsIndex: this.props.row[1],
            loading: false,
        };
    }

    componentDidMount() {
        if (!this.state.loading) {
            this.displayButtonGreen();
        }
    }


    displayButtonGreen = () => {
        if (this.props.row[3].length > 0) {
            this.props.row[3].forEach(item => {
                if (this.props.row[0] === item[0] && this.props.row[1] === item[1]) {
                    console.log('button green', this.props.row, item)
                    this.setState({
                        selected: true,
                        buttonColor: "btn-custom success",
                        iconDisplay: "fa fa-check",
                        oddsDisplay: null,
                        itemId: item[2],
                    })
                }
            })
        }

        this.setState({loading: true})
    }

    selectedOdds = () => {
        if (!this.state.selected) {
            this.setState({
                selected: true,
                buttonColor: "btn-custom",
                iconDisplay: "hide fa fa-check",
                oddsDisplay: "hide",
                textDisplay: "divTrans btn-successOnClick",
                text: "Ajouté",
                loading: true
            })
            this.props.addOddsToCart([this.state.betId, this.state.oddsIndex]);
        } else {
            this.setState({
                selected: false,
                buttonColor: "btn-custom",
                iconDisplay: "hide fa fa-check",
                oddsDisplay: "hide",
                textDisplay: "divTrans btn-dangerOnClick",
                text: "Supprimé",
                loading: true
            })
            this.props.removeOddsFromBetBoard(this.state.itemId);
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {

        //   console.log('updateOddds', this.props, this.state)
        if (
            this.props.row[0] !== this.state.betId || this.props.row[1] !== this.state.oddsIndex) {
            console.log('yes')
            // this.setState({loading : false})
            //   this.displayButtonGreen()
        }

    }


    render() {
        if (this.state.loading) {
            return (
                <td className="text-center" style={{width: "20%"}}>
                    <button className={this.state.buttonColor} role="button" aria-pressed="true"
                            onClick={this.selectedOdds}>
                        <i className={this.state.iconDisplay}/>
                        <em className={this.state.oddsDisplay}>
                            {this.props.row[2][1]}
                        </em>
                        <div className={this.state.textDisplay}>
                            <div className="divText" data-textadded="Ajouté"
                                 data-textremoved="Supprimé">{this.state.text}</div>

                        </div>

                    </button>
                </td>
            );


        } else {
            return (
                <td className="text-center">

                </td>
            )
        }


    }
}

export default OddsList;
