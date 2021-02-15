import React, {Component} from 'react';
import {AsideLeft} from "./AsideLeft";
import {Middle} from "./Middle";
import {AsideRight} from "./AsideRight";

class App extends Component{
    constructor(props){
        super(props);
        this.state = {
            loadingCart: false,
            loadingBetBoard: false,
            cartData: [],
            eventData: [],
            buttonStatus: []
        }
    }

    componentDidMount() {
        this.fetchCart();
        this.fetchBetBoard();

    }

    fetchCart() {
        this.setState({loadingCart: false});
        const url = `/api/cart`;
        fetch(url, {method: 'get'})
            .then(function (response) {
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json});
               // this.calculateAmount();
                this.displayItemFromCart();

            });
    }

    fetchBetBoard() {
        this.setState({loadingBetBoard: false});
        const url = `/api/home`;

        fetch(url, {method: 'get'})
            .then(function (response) {
                return response.json();
            })
            .then(json => {
                this.setState({eventData: json, loadingBetBoard: true});
            });
    }


    displayItemFromCart = () => {
        if(this.state.cartData && this.state.loadingCart === false){
            console.log('this.state.cartData', this.state.cartData)

            let buttonStatus = [];
            this.state.cartData.items.forEach(item => {
                buttonStatus.push([item.bet.id, item.expectedBetResult, item.id]);
            })

            this.setState({buttonStatus : buttonStatus,  loadingCart: true});
            console.log("display button", this.state)
        }else{
            this.setState({buttonStatus: [], loadingCart:true})
        }
    }


    addOddsToCart = (props) => {
        console.log('addOddsToCart', props)
        const url = `/api/cart/add/` + props[0] + `/` + props[1];
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loadingCart: false});
                this.displayItemFromCart();
            });
    }

    removeOddsFromBetBoard = (props) => {
        console.log('removeOddsFromBetBoard', props)
        const url = `/api/cart/remove/` + props;
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loadingCart: false});
                this.displayItemFromCart();
        });
    }


    editOddsFromCart = (props) => {
        console.log('editOddsFromCart', props)

        const url = `/api/cart/changeBetAmount/` + props[0].id;
        fetch(url, {
            method: 'post', body: props[1]
        })
            .then(function (response) {
            console.log(response);
            return response.json();
        })
            .then(json => {
                this.setState({cartData: json, loadingCart: false});
                this.displayItemFromCart();
            });
    }

    removeOddsFromCart = (props) => {
        console.log('removeOddsFromCart', props)
        const url = `/api/cart/remove/` + props[0].id;
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loadingCart: false});
                this.displayItemFromCart();
            });

    }


    componentDidUpdate(prevProps, prevState, snapshot) {

        console.log('udapte App')
}


    render() {
        if(this.state.loadingBetBoard && this.state.loadingCart){
            console.log('render App')
            return (
        <section className="container-fluid" id="page-content">
            <div className="row">
                <div className="col-sm-12 col-md-3">
                    <AsideLeft/>
                </div>
                <div className="col-sm-12 col-md-6">
                    <section className="central">
                        <div className="container-fluid p-0">
                            <div className="row mt-4">
                                <Middle  addOddsToCart = {this.addOddsToCart}
                                         removeOddsFromBetBoard = {this.removeOddsFromBetBoard}
                                         eventData = {[this.state.eventData ,this.state.buttonStatus]}
                                />
                            </div>
                        </div>
                    </section>
                </div>
                <div className="col-sm-12 col-md-3">
                    <AsideRight cartData = {this.state.cartData}
                                editOddsFromCart = {this.editOddsFromCart}
                                removeOddsFromCart = {this.removeOddsFromCart}
                    />
                </div>
            </div>
        </section>
            );
        }else{
            return (
                <div>2 {( Date.now()) }</div>
            );
        }
    }
}


export default App;