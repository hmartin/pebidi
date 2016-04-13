var selection = window.getSelection();

if (selection) {

	var parentNode = selection.anchorNode.parentNode;
	var node = document.createElement("span");
	node.style.cssText = 'background-color: red;';
	var textnode = document.createTextNode(" Word " + selection +" added! ");
	node.appendChild(textnode);
	parentNode.appendChild(node);
}