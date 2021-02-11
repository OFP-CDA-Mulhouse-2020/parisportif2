import React, {Component} from 'react';
import OddsList from "./OddsList";

class EventList extends Component{

    selectedBet = (props) => {
        this.props.fetchBets(props);
    }

        render() {
            return (
                this.props.eventData.map( (row, index)  => (
                <tr key={index}>
                    <td className="" colSpan="2">
                        <p>
                            {row.teams[0]} <br/>
                            {row.teams[1]} <br/>
                            {row.date} <br/>
                            {row.eventName}<br/>
                        </p>
                    </td>
                    <OddsList oddsListData={row} selectedBet={this.selectedBet}/>
                </tr>
            ))
            );
        }
}




export default EventList;
