import React, {Component, Fragment} from 'react';
import EventData from '../data/event.json';
import EventList from "./Event";

 class BetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             EventData : EventData,
             CartData : [],
             loading :false,
         }
     }

     fetchBets = (props) => {
        console.log('odds', props);
         //      this.setState({coords: coordinates, loading: true});

        // const url = `/api/cart/add/selectedBet?betId=` + this.state.coords.lat + `&selectedOdds=` + this.state.coords.lng;

          const url = `/api/cart/add/` + props[0] + `/` + props[1];

         fetch(url, {method: 'get'})
             .then(function (response) {
                 console.log(response);
                 return response.json();
             })
             .then(json => {
                 this.setState({marketData: json, loading: true});
             });
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
                        <EventList eventData={this.state.EventData} fetchBets = {this.fetchBets} />
                     </tbody>
                 </table>
            </Fragment>
         );
    }

 }


export default BetBoard;
