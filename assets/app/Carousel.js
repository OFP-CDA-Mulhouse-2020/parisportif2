import React from 'react';

export const Carousel = () => {
    return (
        <div className="col-sm-12 p-0">
            <div id="carouselExampleIndicators" className="carousel slide" data-bs-ride="carousel">
                <ol className="carousel-indicators">
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" className="active"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
                </ol>
                <div className="carousel-inner" style={{height:'20vw', minHeight: '280px'}}>
                    <div className="carousel-item h-100 active">
                        <img src="https://placehold.it/1600x900/adadad/eaeaea?text=Football" className="d-block w-100"
                             alt="..." style={{objectFit:'cover', width: 'auto', height:'100%'}} />
                    </div>
                    <div className="carousel-item h-100">
                        <img src="https://placehold.it/1600x900/e3e3e3/adadad?text=Basket" className="d-block w-100"
                             alt="..." style={{objectFit:'cover', width: 'auto', height:'100%'}} />
                    </div>
                    <div className="carousel-item h-100">
                        <img src="https://placehold.it/1600x900/a3a3a3/cbcbcb?text=Tennis" className="d-block w-100"
                             alt="..." style={{objectFit:'cover', width: 'auto', height:'100%'}} />
                    </div>
                </div>
                <a className="carousel-control-prev" href={`#carouselExampleIndicators`} role="button"
                   data-bs-slide="prev">
                    <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span className="visually-hidden">Previous</span>
                </a>
                <a className="carousel-control-next" href={"#carouselExampleIndicators"} role="button"
                   data-bs-slide="next">
                    <span className="carousel-control-next-icon" aria-hidden="true"></span>
                    <span className="visually-hidden">Next</span>
                </a>
            </div>
        </div>
    );
}