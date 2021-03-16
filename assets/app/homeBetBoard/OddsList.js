import React, {Component, Fragment} from 'react';

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

        this.initialize = this.state;

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

    componentDidUpdate(prevProps, prevState, snapshot) {

        if(prevProps.row[0] !== this.props.row[0] || prevProps.row[1] !== this.props.row[1])
        {
            this.setState({
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
            });
            this.displayButtonGreen();
        }

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
                <Fragment>

                </Fragment>
            )
        }


    }
}

export default OddsList;
