

document.addEventListener('selectionchange', function() {
    var selection = window.getSelection().toString().trim();
	//window.getSelection().anchorNode.parentNode
    chrome.runtime.sendMessage({
        request: 'updateContextMenu',
        selection: selection
    });
});
