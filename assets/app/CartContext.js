import React, {createContext, useState, Component} from 'react';


export const Theme = {
  dark : {
    background : '#000',
    color : '#FFF',
    divBackground : '#858585',
    divColor : '#acdeff',
    navBackground :'#858585'
  },

  light : {
    background :'#FFF',
    color : '#000',
    divBackground : '#FFF',
    divColor : '#616161',
    navBackground :'#eeeeee'
  }
}

export const CartContext = React.createContext({
  theme: Theme.dark,
  toggleTheme: () => {},
});

