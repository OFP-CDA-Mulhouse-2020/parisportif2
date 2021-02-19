import React from 'react';


export const cartUpdate = { notUpdate:true, shouldUpdate:false }

export const CartContext = React.createContext({
  cartUpdate: cartUpdate.notUpdate,
  toggleTheme: () => {},
});

