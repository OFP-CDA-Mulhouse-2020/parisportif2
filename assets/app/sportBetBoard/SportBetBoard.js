import React, {Component, Fragment} from 'react';
import SportEventList from "./SportEventList";

 class SportBetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             listOfBetData: this.props.sportEventData[0],
         }
         console.log(this.props.sportEventData)
     }




     render() {
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
                         <SportEventList eventData={[this.state.listOfBetData , this.props.sportEventData[1]]}
                                         addOddsToCart = {this.props.addOddsToCart}
                                         removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                         />
                         </tbody>
                     </table>
                 </Fragment>
             );

    }

 }


export default SportBetBoard;
