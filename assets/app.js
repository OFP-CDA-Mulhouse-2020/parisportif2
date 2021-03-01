/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/custom.css';
// start the Stimulus application
import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom';
import App from "./app/App";
// import login from "./js/login";
import customBadge from "./js/badge";
import profileInformation from "./js/profileInformation";
import walletLimitAmount from './js/walletLimitAmount';

/*
function App() {
    return (
        <Cart/>
    )
}

function App2() {
    return (
        <BetBoard/>
    )
}
*/


if(document.querySelector('#page-content')){

    ReactDOM.render(<App/>, document.querySelector('#page-content'));
}

/*
ReactDOM.render(<BetBoard/>, document.querySelector('#betBoard'));*/


// login();
customBadge();
profileInformation();
walletLimitAmount();