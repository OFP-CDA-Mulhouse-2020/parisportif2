import React, {Component, Fragment} from 'react';

const Odds = (props) => {

    const rows = props.eventData.map((row, index) => {
        console.log(row, index);
        return (
        <td className="text-center">
            <button className="btn btn-secondary btn-lg active" role="button" aria-pressed="true"
                    onClick={props.handleChange} >
                {row}
            </button>
        </td>
        )
    })

    return <Fragment>{rows}</Fragment>
}

export default Odds;
