import React, {Component, Fragment} from 'react';
import Odds from "./Odds";

const Event = (props) => {

    const rows = props.eventData.map((row, index) => {
        console.log(row, index);

        return (
            <tr key={index}>
                <td className="" colSpan="2">
                    <p>
                        {row.teams[0]} <br/>
                        {row.teams[1]} <br/>
                        {row.date} <br/>
                        {row.eventName}<br/>
                    </p>
                </td>
                <Odds eventData={row.odds}  value={props.handleChange}/>
            </tr>
        )
    })

    return <Fragment>{rows}</Fragment>
}

export default Event;
