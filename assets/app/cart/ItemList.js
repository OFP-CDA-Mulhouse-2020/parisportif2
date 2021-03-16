import React, {Component} from 'react';

class ItemList extends Component {

    render() {
        return (
            this.props.cartData.map((row, index) => (
                    <Item row={row} key={index}
                          editOddsFromCart = {this.props.editOddsFromCart}
                          removeOddsFromCart = {this.props.removeOddsFromCart}
                    />
            ))
        );
    }
}



class Item extends Component{
    constructor(props) {
        super(props);
        this.state = {
            display: true,
            itemData: this.props.row,
            itemAmount: this.props.row.amount,
            itemId : this.props.row.id,
        };
    }

    editItemAmount = (e) => {
        this.setState({itemAmount : e.target.value});
        this.props.editOddsFromCart([this.state.itemData, e.target.value])
    }

    removeItemFromCart = (props) => {
        this.props.removeOddsFromCart([this.state.itemData, null]);
        this.setState({display: false});
    }

    render() {
        if(this.state.display) {
            return (
                    <div className="row">
                        <div className="col-10">

                            <p>
                                {this.state.itemData.bet.event.teams[0].name}<br/>
                                {this.state.itemData.bet.event.teams[1].name}<br/>
                                {this.state.itemData.bet.event.date}<br/>
                                {this.state.itemData.bet.event.name} - {this.state.itemData.bet.event.competition.name}<br/>
                                {this.state.itemData.bet.typeOfBet.name} - {this.state.itemData.bet.listOfOdds[this.state.itemData.expectedBetResult][0]}<br/>

                            </p>
                            <form action="" method="post" className="mb-2">
                                <div className="form-group">
                                <label htmlFor="change_amount"> Mise :</label>
                                    <div className="input-group">

                                    <input className="form-control" type="number" value={`${this.state.itemAmount}`} name="change_amount"
                                       onChange={this.editItemAmount}/>
                                        <div className="input-group-append">
                                            <span className="input-group-text"> @{this.state.itemData.recordedOdds}</span>
                                        </div>

                                    </div>
                                </div>

                            </form>

                        </div>

                        <div className="col-2">
                            <button className="btn btn-danger" onClick={this.removeItemFromCart}>
                                <i className="fa fa-times" aria-hidden="true"/>
                            </button>
                        </div>
                        <hr/>

                    </div>
            );
        }else{
            return (
                <div>

                </div>
                );

        }

    }
}


export default ItemList;
