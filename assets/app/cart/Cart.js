import React, {Component, Fragment} from 'react';
import ItemList from "./ItemList";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cartData: null,
            loading: false,
            sum: 0,
            expectedSum: 0,
            update: true,
        }
    }

    componentDidMount() {
        this.setState({cartData:this.props.cartData, loading:true});
        this.calculateAmount();
    }

    componentDidUpdate(props) {
        // Utilisation classique (pensez bien à comparer les props) :

        if(this.state.loading !== true)
        {
            this.calculateAmount();

        }

    }

    calculateAmount = () => {
        let sum = 0;
        let expectedSum = 0;
        if(this.props.cartData){
            const itemsArray = [...this.props.cartData.items];
            itemsArray.forEach(item => {
            sum += item.amount;
            expectedSum += item.amount * item.recordedOdds;
        });
        }

        this.setState({
            sum: sum,
            expectedSum: Math.round(expectedSum * 100) / 100,
        });
    }


    render() {
        if (this.state.cartData) {

            return (
                    <section className="bet-ticket">
                        <h6>Ticket de paris :</h6>
                        <hr/>

                        <ItemList cartData={this.state.cartData.items}
                                  editOddsFromCart = {this.props.editOddsFromCart}
                                  removeOddsFromCart = {this.props.removeOddsFromCart}
                        />

                        <div className="row">
                            <div className="col-6">
                                Total des mises : {this.state.sum} EUR
                            </div>

                            <div className="col-6">
                                Gains potentiels : {this.state.expectedSum} EUR
                            </div>
                        </div>

                        <div className="col-12 d-flex justify-content-center">
                            <a type="button" className="btn btn-danger btn-sm" href="/app/cart/payment">Pariez !</a>
                        </div>
                    </section>
            );
        } else {
            return (
                <section className="bet-ticket">
                    <h6>Ticket de paris :</h6>
                    <hr/>
                    <p>Ajouter des paris</p>
                    <div className="col-12 d-flex justify-content-center">
                        <button type="button" className="btn btn-secondary btn-sm" disabled>Pariez !</button>
                    </div>
                </section>

            );
        }

    }

}

export default Cart;
