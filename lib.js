

document.addEventListener( 'DOMContentLoaded', function() {
    var elms = document.getElementsByClassName( 'splide' );
    for ( var i = 0; i < elms.length; i++ ) {
        new Splide( elms[ i ] , {
            type   : 'slide',
            drag   : 'free',
            snap   : true,
            gap: 10,
            perPage: 5,
            perMove: 1,
            pagination: false,
            padding: {left: 2, right: 2},
            classes: {
                prev  : 'splide__arrow--prev',
                next  : 'splide__arrow--next',
            },
            breakpoints: {
                980: {
                    perPage: 4,
                },
                840: {
                    perPage: 3
                },
                560: {
                    perPage: 2
                },
                360: {
                    perPage: 1
                }
            }
        }).mount();
    }
} );