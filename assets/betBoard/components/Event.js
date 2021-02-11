import React, {Component} from 'react';
import OddsList from "./OddsList";

class EventList extends Component{

    selectedBet = (props) => {
       // this.props.fetchBets(props);
        console.log('OK')
    }

        render() {
            return (
                this.props.eventData.map( (row, index)  => (
                <tr key={index}>
                    <td className="" colSpan="2">
                        <p>
                            {row.event.teams[0].name} <br/>
                            {row.event.teams[1].name} <br/>
                            {row.event.date} <br/>
                            {row.event.name} - {row.event.competition.name}<br/>
                            <a href={`/app/sport/${row.event.sport.name}/event/${row.id}`}>
                                + de paris
                            </a>
                    </p>
                    </td>
                   <OddsList oddsListData={row} selectedBet={this.selectedBet}/>
                </tr>
            ))
            );
        }
}




export default EventList;
