import React from 'react';
import {NavLeft} from "./NavLeft";

export const AsideLeft = () => {
    return (
    <aside className="container-fluid aside-left">
        <div className="row card mt-4">
            <div className="container-fluid col-sm-12 advertising-insert-1" style={{height:'20vw', minHeight: '280px'}}>
                Espace Publicitaire 1
            </div>
        </div>
        <NavLeft/>
    </aside>
    );
}