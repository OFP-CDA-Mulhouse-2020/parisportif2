import React, {Component, Fragment} from 'react';
import {AsideRight} from "./partials/AsideRight";
import {NavLeft} from "./partials/NavLeft";
import EventBetBoard from "./eventBetBoard/EventBetBoard";

class SelectedEvent extends Component{
    constructor(props){
        super(props);
        this.state = {
            loading:false,
            cartData: null,
            eventData: null,
            listOfBetData: null,
            buttonStatus: [],
            eventId: this.props.match.params.eventId,
            sportName: this.props.match.params.sportName,

        }

        console.log(this.props)
    }

    componentDidMount() {

        this.fetchBetAndCart();
    }

    fetchBetAndCart() {
        this.setState({loading: false});
        const url = `/api/event/` + this.state.sportName + `/` + this.state.eventId;

        fetch(url, {method: 'get'})
            .then(function (response) {
                return response.json();
            })
            .then(json => {
                this.setState({
                    listOfBetData: json["listOfBet"],
                    eventData: json["event"],
                    cartData: json["cart"]
                });
                this.displayItemFromCart();

            });
    }

    displayItemFromCart = () => {
        if(this.state.cartData && this.state.loading === false){
            let buttonStatus = [];
            this.state.cartData.items.forEach(item => {
                buttonStatus.push([item.bet.id, item.expectedBetResult, item.id]);
            })
            this.setState({buttonStatus : buttonStatus,  loading: true});
        }else{
            this.setState({buttonStatus: [], loading:true})
        }
    }


    addOddsToCart = (props) => {
        const url = `/api/cart/add/` + props[0] + `/` + props[1];
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loading: false});
                this.displayItemFromCart();
            });
    }

    removeOddsFromBetBoard = (props) => {
        const url = `/api/cart/remove/` + props;
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loading: false});
                this.displayItemFromCart();
        });
    }


    editOddsFromCart = (props) => {
        const url = `/api/cart/changeBetAmount/` + props[0].id;
        fetch(url, {
            method: 'post', body: props[1]
        })
            .then(function (response) {
            console.log(response);
            return response.json();
        })
            .then(json => {
                this.setState({cartData: json, loading: false});
                this.displayItemFromCart();
            });
    }

    removeOddsFromCart = (props) => {
        const url = `/api/cart/remove/` + props[0].id;
        fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
            })
            .then(json => {
                this.setState({cartData: json, loading: false});
                this.displayItemFromCart();
            });

    }

    render() {

        if(this.state.loading){
            console.log(this.state.eventData ,this.state.buttonStatus);

            return (
        <section className="container-fluid" id="page-content">
            <div className="row">
                <div className="col-sm-12 col-md-3">
                    <aside className="container-fluid aside-left">

                    <NavLeft/>
                    </aside>

                </div>
                <div className="col-sm-12 col-md-6">
                    <section className="central">
                        <div className="container-fluid p-0">
                            <div className="row mt-4">
                                <div className="col-sm-12 bet-board card p-2" id="betBoard">
                                    <EventBetBoard  addOddsToCart = {this.addOddsToCart}
                                               removeOddsFromBetBoard = {this.removeOddsFromBetBoard}
                                               sportEventData = {this.state}/>
                                </div>
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
                <Fragment>

    <div className="loader">
        <div className="loader-circle c1"/>
        <div className="loader-circle c2"/>
        <div className="loader-circle c3"/>
        <div className="loader-circle c4"/>
        <div className="loader-circle c5"/>
        <div className="loader-circle c6"/>
    </div>

    <div className="loader-block">
            <section className="container-fluid" id="page-content">
                <div className="row">
                    <div className="col-sm-12 col-md-3">
                        <aside className="container-fluid aside-left">

                            <NavLeft/>
                        </aside>
                    </div>
                    <div className="col-sm-12 col-md-6">
                        <section className="central">
                            <div className="container-fluid p-0">
                                <div className="row mt-4">
                                    <div className="col-sm-12 bet-board card p-2" id="betBoard">

                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div className="col-sm-12 col-md-3">
                        <aside className="container-fluid aside-right">
                            <div className="row card mt-4 p-2">

                            </div>
                            <div className="row card mt-4 p-2">
                                <section className="col-sm-12 bet-search">
                                    <form className="row g-3">
                                        <div className="col-12">
                                            <label htmlFor="inputBetSearch" className="form-label">
                                                <h6>Chercher un pari</h6>
                                            </label>
                                            <input type="text" className="form-control" id="inputBetSearch" placeholder="placeholder" />
                                        </div>
                                        <div className="col-12 d-flex justify-content-center">
                                            <button type="submit" className="btn btn-danger btn-sm">Rechercher</button>
                                        </div>
                                    </form>
                                </section>
                            </div>
                            <div className="card row mt-4">
                                <section className="col-sm-12 mt-3 advertising-insert-2" style={{height:'200px' }}>
                                    Espace Publicitaire 2
                                </section>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
    </div>

                </Fragment>
            );
        }
    }
}


export default SelectedEvent;

