

document.addEventListener('selectionchange', function() {
    var selection = window.getSelection().toString().trim();
	//window.getSelection().anchorNode.parentNode
    chrome.runtime.sendMessage({
        request: 'updateContextMenu',
        selection: selection
    });
});

/*

if (document.title.indexOf("Google") != -1) {
    //Creating Elements
    var btn = document.createElement("BUTTON")
    var t = document.createTextNode("CLICK ME");
    btn.appendChild(t);
    //Appending to DOM 
    document.body.appendChild(btn);
}
*/