/**
 * Theme JavaScript for Panna Wild Tours.
 * Enhances smooth scrolling and modern browser behaviors.
 */

(function () {
    'use strict';

    document.documentElement.classList.add( 'js-enabled' );

    function initSmoothScroll() {
        var anchorLinks = document.querySelectorAll( 'a[href^="#"]:not([href="#"])' );

        anchorLinks.forEach( function ( link ) {
            link.addEventListener( 'click', function ( event ) {
                var targetId = this.getAttribute( 'href' ).slice( 1 );
                var targetElement = document.getElementById( targetId );

                if ( targetElement ) {
                    event.preventDefault();
                    targetElement.scrollIntoView( { behavior: 'smooth', block: 'start' } );
                }
            } );
        } );
    }

    if ( 'scrollBehavior' in document.documentElement.style ) {
        initSmoothScroll();
    }

    function initUpiCopyButtons() {
        var copyButtons = document.querySelectorAll( '.wildtours-upi-copy' );
        copyButtons.forEach( function ( button ) {
            button.addEventListener( 'click', function () {
                var value = button.getAttribute( 'data-copy-text' );
                if ( ! value ) {
                    return;
                }

                navigator.clipboard.writeText( value ).then( function () {
                    var originalText = button.textContent;
                    button.textContent = 'Copied!';
                    setTimeout( function () {
                        button.textContent = originalText;
                    }, 2000 );
                } ).catch( function () {
                    button.textContent = 'Copy failed';
                } );
            } );
        } );
    }

    initUpiCopyButtons();
})();
