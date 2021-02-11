import React, {Component, Fragment} from 'react';
import EventData from '../data/event.json';
import EventList from "./Event";

 class BetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             EventData : [],
             CartData : [],
             loading :false,
         }
     }

     componentDidMount() {
         this.setState({loading: false});

         const url = `/api/home`;

         fetch(url, {method: 'get'})
             .then(function (response) {
                 console.log(response);
                 return response.json();
             })
             .then(json => {
                 console.log(json);
                 this.setState({EventData: json, loading: true});
             });
     }


     render() {
         if(this.state.loading){

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
                        <EventList eventData={this.state.EventData} />
                     </tbody>
                 </table>
            </Fragment>
         );
             }
             else{
                 return (
                 <div>wait</div>
                 );
         }

    }

 }


export default BetBoard;
