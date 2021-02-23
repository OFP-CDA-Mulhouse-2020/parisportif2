import React, {Component, Fragment} from 'react';
import EventList from "./EventList";
import Filter from "./Filter";

 class HomeBetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             eventData: [],
             eventFilterBySport: [],
             selectedSport :'Football',
         }
     }

     componentDidMount() {
        this.setState({eventData: this.props.eventData[0]})
         this.filterBySport(this.state.selectedSport);
     }

     handleChange = (selectedSport) => {
         this.setState({selectedSport:selectedSport});
         this.filterBySport(selectedSport);
     }

     filterBySport = (selectedSport) => {
         const eventBySport = this.props.eventData[0].filter(bet => bet.event.sport.name === selectedSport);
         this.setState({eventFilterBySport: eventBySport})
     }

     render() {
             return (
                 <Fragment>
                     <h4>Top du moment</h4>
                     <Filter eventData = {this.props.eventData} handleChange = {this.handleChange} />
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
                         <EventList eventData={[this.state.eventFilterBySport , this.props.eventData[1]]}
                                    addOddsToCart = {this.props.addOddsToCart}
                                    removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                         />
                         </tbody>
                     </table>
                 </Fragment>
             );

    }

 }


export default HomeBetBoard;
