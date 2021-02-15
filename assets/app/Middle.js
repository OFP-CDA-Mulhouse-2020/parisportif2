import React, {Component, Fragment} from 'react';
import {Carousel} from "./Carousel";
import BetBoard from "../betBoard/components/BetBoard";

export class Middle extends Component {

    render(){
        return (
            <Fragment>
                <Carousel />
                <div className="col-sm-12 mt-4 bet-board card p-2" id="betBoard">
                    <BetBoard  addOddsToCart = {this.props.addOddsToCart}
                               removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                               eventData = {this.props.eventData}/>
                </div>
            </Fragment>

        );
    }

}