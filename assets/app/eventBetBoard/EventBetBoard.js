import React, {Component, Fragment} from 'react';
import EventList from "./EventList";

 class EventBetBoard extends Component{
     constructor(props){
         super(props);
         this.state = {
             eventData: this.props.sportEventData.eventData,
             eventFilterBySport: [],
             selectedSport :'Football',
         }
     }


     render() {
             return (
                 <Fragment>
                     <h5>{ this.state.eventData.sport.name }  - { this.state.eventData.competition.name } - { this.state.eventData.name } :</h5>

                     <h5>{this.state.eventData.teams[0].name } - { this.state.eventData.teams[1].name }</h5>
                     <h6>{ this.state.eventData.date} ({ this.state.eventData.timezone}) - Lieu : { this.state.eventData.location }</h6>


                 </Fragment>
             );

    }

 }


export default EventBetBoard;
