import React, {Component} from 'react';
import {BrowserRouter as Router, Redirect, Route, Switch} from "react-router-dom";
import Home from "./Home";
import SelectedEvent from "./SelectedEvent";
import SelectedSport from "./SelectedSport";

class App extends Component{
    constructor(props){
        super(props);

    }

    render() {

        return (
        <Router>
                <Switch>
{/*                    <Route exact path="/">
                        <Redirect to="/app" /> exact component={Home} />
                    </Route>*/}
                        <Route path="/app" exact component={Home}/>
                        <Route path="/app/sport/:sportName" exact component={SelectedSport} />
                        <Route path="/app/event/:sportName/:eventId" exact component={SelectedEvent} />
                </Switch>
        </Router>
            );
        }

}


export default App;