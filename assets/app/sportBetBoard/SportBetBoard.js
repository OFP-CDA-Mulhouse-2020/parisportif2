import React, {Component, Fragment} from 'react';
import SportEventList from "./SportEventList";

 class SportBetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             listOfBetData: this.props.sportEventData[0],
         }
     }


     render() {

         if(this.props.sportEventData[2] === 'football'){
             return (
                 <Fragment>
                     <h4>Top du moment</h4>
                     <table className="table table-hover fw-bold">
                         <thead>
                         <tr>
                             <th scope="col" colSpan="2">Match</th>
                             <th className="text-center" scope="col">Equipe à domicile</th>
                             <th className="text-center" scope="col">Match Nul</th>
                             <th className="text-center" scope="col">Equipe à l'extérieur</th>

                         </tr>
                         </thead>
                         <tbody>
                         <SportEventList eventData={[this.state.listOfBetData , this.props.sportEventData[1], this.props.sportEventData[2]]}
                                         addOddsToCart = {this.props.addOddsToCart}
                                         removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                         />
                         </tbody>
                     </table>
                 </Fragment>
             );
         }
         if(this.props.sportEventData[2] === 'basketball'){
             return (
                 <Fragment>
                     <h4>Top du moment</h4>
                     <table className="table table-hover fw-bold">
                         <thead>
                         <tr>
                             <th scope="col" colSpan="2">Match</th>
                             <th className="text-center" scope="col">Equipe à domicile</th>
                             <th className="text-center" scope="col">Equipe à l'extérieur</th>

                         </tr>
                         </thead>
                         <tbody>
                         <SportEventList eventData={[this.state.listOfBetData , this.props.sportEventData[1], this.props.sportEventData[2]]}
                                         addOddsToCart = {this.props.addOddsToCart}
                                         removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                         />
                         </tbody>
                     </table>
                 </Fragment>
             );
         }
         else {
             return (
                 <Fragment>

                 </Fragment>
             );
         }


    }

 }


export default SportBetBoard;
