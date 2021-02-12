import React, {Component, Fragment} from 'react';

class ItemList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cartData: [],
        }
    }

    render() {
        return (
            this.props.cartData.map((row, index) => (
                    <Item row={row} key={index}
                          deleteItem = {this.props.deleteItem}
                          editItemAmount = {this.props.editItemAmount}
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
        this.props.editItemAmount([this.state.itemData, e.target.value])
    }

    removeItemFromCart = (props) => {
        this.props.deleteItem([this.state.itemData, 0]);
        this.setState({display: false});
    }

    render() {
        if(this.state.display) {
            return (
                <Fragment>
                    <div className="row">
                        <div className="col-10">

                            <p>
                                {this.state.itemData.bet.event.teams[0].name}<br/>
                                {this.state.itemData.bet.event.teams[1].name}<br/>
                                {this.state.itemData.bet.event.date}<br/>
                                {this.state.itemData.bet.event.name} - {this.state.itemData.bet.event.competition.name}<br/>
                                {this.state.itemData.bet.typeOfBet.betType}<br/>
                                @{this.state.itemData.recordedOdds}<br/>
                            </p>
                            <form action="" method="post">
                                <label htmlFor="change_amount"> Mise :</label>
                                <input type="number" value={`${this.state.itemAmount}`} name="change_amount"
                                       onChange={this.editItemAmount}/>

                            </form>

                        </div>

                        <div className="col-2">
                            <button className="btn btn-danger" onClick={this.removeItemFromCart}>
                                <i className="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <hr/>

                    </div>
                </Fragment>
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
