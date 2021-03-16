import React, {Component, Fragment} from 'react';

class SportOddsList extends Component {

    render() {
            return (
                this.props.oddsListData[0].listOfOdds.map((row, index) => (
                    <Odds row={[this.props.oddsListData[0].id, index, row, this.props.oddsListData[1], this.props.oddsListData[2]]} key={index}
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

    render() {
        if (this.state.loading && this.props.row[4] === 'football') {
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
        }
        if (this.state.loading && this.props.row[4] === 'basketball') {
            return (
                <td className="text-center" style={{width: "30%"}}>
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


        }
        else {
            return (
                <Fragment>

                </Fragment>
            )
        }


    }
}

export default SportOddsList;
