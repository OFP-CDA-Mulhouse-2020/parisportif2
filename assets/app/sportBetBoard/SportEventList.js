import React, {Component} from 'react';
import SportOddsList from "./SportOddsList";

class SportEventList extends Component{

        render() {

            return (
                this.props.eventData[0].map( (row, index)  => (
                <tr key={index}>
                    <td className="" colSpan="2">
                        <p>
                            {row.event.teams[0].name} <br/>
                            {row.event.teams[1].name} <br/>
                            {row.event.date} <br/>
                            {row.event.name} - {row.event.competition.name}<br/>
                            <a href={`/app/event/${row.event.sport.name}/${row.event.id}`}>
                                + de paris
                            </a>
                    </p>

                    </td>
                   <SportOddsList oddsListData={[row, this.props.eventData[1]]}
                                  addOddsToCart = {this.props.addOddsToCart}
                                  removeOddsFromBetBoard = {this.props.removeOddsFromBetBoard}
                   />
                </tr>
            ))
            );
        }
}




export default SportEventList;
