import React, {Component, Fragment} from 'react';
import Cart from "../cart/components/Cart";
import BetBoard from "../betBoard/components/BetBoard";

class App extends Component{
    constructor(props){
        super(props);
        this.state = {
            loading :false,
        }
        console.log(this.state);
    }

    selectedBet = (props) => {
        console.log('betttttttt')
    }

    updateCart = (props) => {
        console.log('cart', props)
    }


    render() {
        if(this.state.loading){
            return (
                <Fragment>
                    <Cart />
                    <BetBoard  updateCart = {this.selectedBet()}/>
                </Fragment>
            );
        }
        else{
            return (
                <div>

                </div>

            );
        }

    }

}

export default App;
