import React, {Component, Fragment} from 'react';
import ItemList from "./ItemList";

class Cart extends Component{
    constructor(props){
        super(props);
        this.state = {
            cartData : [],
            loading :false,
            sum : [],
            expectedSum : [],
            update:true,
        }
    }

    componentDidMount() {
        this.fetchCart();
    }

    fetchCart(){
        this.setState({loading: false});
        const url = `/api/cart`;
        fetch(url, {method: 'get'})
            .then(function (response) {
                return response.json();
            })
            .then(json => {
                this.setState({cartData : json, loading: true});
                this.calculateAmount();
            });
    }

    deleteItem = (props) => {
        this.calculateAmount(props);
        const url = `/api/cart/remove/` + props[0].id;
       fetch(url, {method: 'get'})
            .then(function (response) {
                console.log(response);
                return response.json();
        });

    }

    editItemAmount = (props) => {
        this.calculateAmount(props);

        const url = `/api/cart/changeBetAmount/` + props[0].id;
        fetch(url, {
            method: 'post', body : props[1]})
            .then(function (response) {
                console.log(response);
                return response.json();
        });

    }

    calculateAmount = (props = null) => {
        const itemsArray = [...this.state.cartData.items];
        let sum = 0;
        let expectedSum = 0;
        itemsArray.forEach(item =>{
            sum += item.amount;
            expectedSum += item.amount * item.recordedOdds;
        }  );

        if(props){
            sum = sum - props[0].amount + parseInt(props[1],10);
            expectedSum = expectedSum -props[0].amount * props[0].recordedOdds + parseInt(props[1],10) * props[0].recordedOdds;
            this.setState({update:false});
        }
        this.setState({sum : sum,
                            expectedSum : Math.round(expectedSum*100)/100
            });

    }

    componentDidUpdate(props) {
        // Utilisation classique (pensez bien à comparer les props) :
        if (this.state.update !== true) {
            console.log('fetch');
            this.setState({update:true});
            this.fetchCart();

        }
    }

    render() {
        if(this.state.loading && this.state.cartData && this.state.update){
            return (
                <Fragment>
                    <h6>Ticket de paris :</h6>
                    <hr/>

                    <ItemList cartData={this.state.cartData.items}
                              deleteItem = {this.deleteItem}
                              editItemAmount = {this.editItemAmount}
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


                </Fragment>
            );
        }
        else{
            return (
                <Fragment>
                <h6>Ticket de paris :</h6>
                <hr/>
                    <p>Ajouter des paris</p>
                    <div className="col-12 d-flex justify-content-center">
                        <button type="button" className="btn btn-secondary btn-sm" disabled>Pariez !</button>
                    </div>
                </Fragment>

        );
        }

    }

}

export default Cart;
