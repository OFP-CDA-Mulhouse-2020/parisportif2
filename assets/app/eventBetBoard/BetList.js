import React, {Component, Fragment} from 'react';
import EventOddsList from "./EventOddsList";

class BetList extends Component{

        render() {

            console.log(this.props)
            return this.props.listOfBet[0].map( (row, index)  => {
                if(row.typeOfBet.name === "1N2"){
                    return (
                        <Fragment key={index}>
                            <hr/>
                                <h4>Résultat du match</h4>
                                <table className="table table-hover fw-bold">
                                    <thead>
                                    <tr>
                                        <th className="text-center" scope="col">Equipe à domicile</th>
                                        <th className="text-center" scope="col">Match Nul</th>
                                        <th className="text-center" scope="col">Equipe à l'extérieur</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <EventOddsList oddsListData={[row, this.props.listOfBet[1]]}
                                                  addOddsToCart = {this.props.addOddsToCart}
                                                  removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                                        />
                                    </tr>
                                    </tbody>
                                </table>

                        </Fragment>


                    );

                }
                if(row.typeOfBet.name === "1-2"){
                    return (
                        <Fragment key={index}>
                            <hr/>
                            <h4>Résultat du match</h4>
                            <table className="table table-hover fw-bold">
                                <thead>
                                <tr>
                                    <th className="text-center" scope="col">Equipe à domicile</th>
                                    <th className="text-center" scope="col">Equipe à l'extérieur</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <EventOddsList oddsListData={[row, this.props.listOfBet[1]]}
                                              addOddsToCart = {this.props.addOddsToCart}
                                              removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                                    />
                                </tr>
                                </tbody>
                            </table>

                        </Fragment>


                    );
                }
                    if(row.typeOfBet.name === "over-under"){
                        return (
                            <Fragment key={index}>
                                <hr/>
                                <h4>Over/Under</h4>
                                <div className="row">

                                <EventOddsList oddsListData={[row, this.props.listOfBet[1]]}
                                               addOddsToCart = {this.props.addOddsToCart}
                                               removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                                />
                                </div>

                            </Fragment>


                        );

                }
                if(row.typeOfBet.name === "score exact"){
                    return (
                        <Fragment key={index}>
                            <hr/>
                            <h4>Score Exact</h4>
                            <div className="row">

                                <EventOddsList oddsListData={[row, this.props.listOfBet[1]]}
                                               addOddsToCart = {this.props.addOddsToCart}
                                               removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                                />
                            </div>

                        </Fragment>


                    );

                }

                if(row.typeOfBet.name === "mi-temps fin de match"){
                    return (
                        <Fragment key={index}>
                            <hr/>
                                <h4>Mi-temps/Fin du match</h4>
                                <div className="row">

                                <EventOddsList oddsListData={[row, this.props.listOfBet[1]]}
                                               addOddsToCart = {this.props.addOddsToCart}
                                               removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                                />
                            </div>

                        </Fragment>


                    );

                }
                    else{
                        return (
                            <Fragment key={index}>

                            </Fragment>
                        );
                    }

            })

        }
}




export default BetList;






