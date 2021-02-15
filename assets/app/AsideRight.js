import React from 'react';
import Cart from "../cart/components/Cart";
import BetBoard from "../betBoard/components/BetBoard";

export const AsideRight = (props) => {

    return (
        <aside className="container-fluid aside-right">
            <div className="row card mt-4 p-2">
                <Cart cartData = {props.cartData}
                      editOddsFromCart = {props.editOddsFromCart}
                      removeOddsFromCart = {props.removeOddsFromCart}
                />
            </div>
            <div className="row card mt-4 p-2">
                <section className="col-sm-12 bet-search">
                    <form className="row g-3">
                        <div className="col-12">
                            <label htmlFor="inputBetSearch" className="form-label">
                                <h6>Chercher un pari</h6>
                            </label>
                            <input type="text" className="form-control" id="inputBetSearch" placeholder="placeholder" />
                        </div>
                        <div className="col-12 d-flex justify-content-center">
                            <button type="submit" className="btn btn-danger btn-sm">Rechercher</button>
                        </div>
                    </form>
                </section>
            </div>
            <div className="card row mt-4">
                <section className="col-sm-12 mt-3 advertising-insert-2" style={{height:'200px' }}>
                    Espace Publicitaire 2
                </section>
            </div>
        </aside>
    );
}