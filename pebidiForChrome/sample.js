'use strict';

var exist = null;
var dict = false;
var type = "";

var $http = angular.injector(["ng"]).get("$http");
$http.get('http://pebidi.com/dict/dict.json').then(function(res) {
	dict = res.data;
});

chrome.runtime.onMessage.addListener(function(msg, sender, sendResponse) {
	if (msg.request === 'updateContextMenu') {
		type = msg.selection;
		if (exist == '') {
			if (exist != null) {
				chrome.contextMenus.remove(exist);
				exist = null;
			}
		}
		else {
			if (dict) {
				var myInjector = angular.injector(["ng"]);
				var $filter = myInjector.get("$filter");
				var sug = $filter('limitTo')($filter('filter')(dict, type, function(actual, expected) {
					return actual.toString().toLowerCase().indexOf(expected.toLowerCase()) == 0;
				}), 1);
				var trans = sug[0];
				var trad = "(traduction not found)";
				if (trans && trans.t) {
					trad = trans.t;
				}
				var title = "Insert " + type + " => " + trad + ' in your pebidi';
			}
			else {
				var title = "Insert " + type + "...  Retry Pebidi is loading...";
			}

			if (exist) {
				var options = {
					title: title,
					contexts: ['selection']
				};
				chrome.contextMenus.update('word', options);
			}
			else {
				var options = {
					id: 'word',
					title: title,
					contexts: ['selection']
				};
				exist = chrome.contextMenus.create(options);
			}
		}
	}
});

chrome.contextMenus.onClicked.addListener(function(info, tab) {

	if (info.menuItemId === "word" && info.selectionText) {
		var sText = info.selectionText;
		var intRegex = /^[a-zA-Z]+$/;
		if (intRegex.test(info.selectionText)) {
			$http.post('http://pebidi.com/api.php/words', {
				'w': sText,
				'id': 1
			}).success(function(data, tab) {
				console.log('success');
				chrome.tabs.executeScript(tab.id, {
					code: 'var config = ' + JSON.stringify(data)
				}, function() {
					chrome.tabs.executeScript(tab.id, {
						file: "notification.js"
					})
				});
			});
		}
	}
});
