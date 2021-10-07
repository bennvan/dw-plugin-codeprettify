/*
 * script.js : code-prettify plugin for DokuWiki
 * register prettyprint() event listener on the window
 * @author Satoshi Sahara <sahara.satoshi@gmail.com>
 */

function initPrettyPrint(event){
        // highlight the code
        prettyPrint();


        document.querySelectorAll('pre.prettyprint, pre.code, pre.file').forEach(elmt => {
            var btn = document.createElement("DIV");
            btn.title = 'Copy to Clipboard';
            btn.classList.add('codeclipboard_button');
            var parent = elmt.parentElement;
            var wrapper;
            if (parent.className == 'code-toolbar'){
                wrapper = true;
            } else {
                wrapper = false;
            }
            // If parent wrapper is present, append to that. 
            if (wrapper) {
                parent.appendChild(btn);
            } else {
                elmt.appendChild(btn);
            }
            
            // Add the click function
            btn.onclick = function() {
                if (wrapper){
                    parent.classList.add('copied');
                    setTimeout(function(){
                        parent.classList.remove('copied');
                    }, 1000);
                } else {
                    elmt.classList.add('copied');
                    setTimeout(function(){
                        elmt.classList.remove('copied');
                    }, 1000);
                }
                var txt = document.createElement('TEXTAREA');
                txt.innerHTML = elmt.innerText;
                elmt.appendChild(txt);
                txt.select();
                document.execCommand('copy');
                elmt.removeChild(txt);
            }

        });

    };
    
    if(window.addEventListener) {
        window.addEventListener("load",initPrettyPrint,false);
    } else if(window.attachEvent) {
        window.attachEvent("onload",initPrettyPrint);
    }