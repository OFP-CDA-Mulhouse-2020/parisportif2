/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
// start the Stimulus application
import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom';
import Cart from './cart/components/Cart';
import BetBoard from './betBoard/components/BetBoard';


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
ReactDOM.render(<App/>, document.querySelector('#cart'));

ReactDOM.render(<App2/>, document.querySelector('#betBoard'));