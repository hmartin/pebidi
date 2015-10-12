var selection = window.getSelection();

if (selection) {

	var parentNode = selection.anchorNode.parentNode;
	var node = document.createElement("span");
	var textnode = document.createTextNode("great");
	node.appendChild(textnode);        // Append the text to <li>
	parentNode.appendChild(node);
}