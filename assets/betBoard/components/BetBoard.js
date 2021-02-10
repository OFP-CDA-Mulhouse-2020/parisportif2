import React, {Component, Fragment} from 'react';
import EventList from '../data/event.json';
import Event from "./Event";

 class BetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             EventList : EventList,
             loading :false,
         }
     }

     handleChange = (props) => {
         console.log('button');
     }

     render() {

         return (
    <Fragment>
             <h4>Football - Top du moment</h4>
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
                <Event eventData={this.state.EventList} handleChange={this.handleChange} />
             </tbody>
         </table>
    </Fragment>
         );
    }

 }


export default BetBoard;
