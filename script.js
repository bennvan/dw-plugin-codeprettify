/*
 * script.js : code-prettify plugin for DokuWiki
 * register prettyprint() event listener on the window
 * @author Satoshi Sahara <sahara.satoshi@gmail.com>
 */

(function(){
    function init(event){
        prettyPrint();
    }
    if(window.addEventListener) {
        window.addEventListener("load",init,false);
    } else if(window.attachEvent) {
        window.attachEvent("onload",init);
    }
})();

jQuery(function () {

    jQuery( 'pre.prettyprint, pre.code' ).each( function() {

        var $elmt = jQuery( this );

        // Add button
        //$btn = jQuery( '<div class="codeclipboard_button_container"><button></button></div>' );

        $btn = jQuery('<div class="codeclipboard_button" title="Copy to clipboard"></div>');

        $elmt.prepend($btn);

        // Setup click behaviour
        $btn.click(function() {
            $elmt.fadeTo(100, 0.3);
            var $txt = jQuery( '<textarea />' );
            $txt.val($elmt.text()).css({ width: "1px", height: "1px" }).appendTo('body');
            $txt.select();
            document.execCommand('copy');
            $txt.remove();
            $elmt.fadeTo(600, 1);
        });

    });

});