import React, {Component, Fragment} from 'react';
import EventList from "./EventList";
import {CartContext, Theme}  from "../../app/CartContext";

 class BetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             eventData : [],
             cartData : [],
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
                 this.setState({eventData: json, loading: true});
             });
     }


     updateCart = (props) => {
         console.log('cart')
     }

     render() {
         if(this.state.loading){

         return (
             <CartContext.Provider value={this.state}>

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
                        <EventList eventData={this.state.eventData}
                                   updateCart = {this.updateCart}

                        />
                     </tbody>
                 </table>
            </Fragment>

             </CartContext.Provider>
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
