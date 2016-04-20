'use strict';
/* global angular */
/* global chrome */

var exist = null;
var dict = false;
var type = "";

var $http = angular.injector(["ng"]).get("$http");
$http.get('http://pebidi.com/dict/dicten.json').then(function(res) {
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
			var title;
			if (dict) {
				var myInjector = angular.injector(["ng"]);
				var $filter = myInjector.get("$filter");
				var sug = $filter('limitTo')($filter('filter')(dict, {w:type}, function(actual, expected) {
					// TODO if finish by s (if exactly, if exactly with non s, else...)
					return actual.toString().toLowerCase().indexOf(expected.toLowerCase()) == 0;
				}), 1);
				var trans = sug[0];
				var trad = "(traduction not found)";
				if (trans && trans.t) {
					trad = trans.t;
					type = trans.w;
				}
				title = "Insert " + type + " => " + trad + ' in your pebidi';
			}
			else {
				title = "Insert " + type + "...  Retry Pebidi is loading...";
			}

			var options;
			if (exist) {
				options = {
					title: title,
					contexts: ['selection']
				};
				chrome.contextMenus.update('word', options);
			}
			else {
				options = {
					id: 'word',
					title: title,
					contexts: ['selection']
				};
				exist = chrome.contextMenus.create(options);
			}
		}
	}
});

chrome.contextMenus.onClicked.addListener(function(info, tab) 
{
	if (info.menuItemId === "word" && info.selectionText) {
		var sText = info.selectionText;
		var intRegex = /^[a-zA-Z]+$/;
		if (intRegex.test(info.selectionText)) {
			$http.post('http://pebidi.com/api_dev.php/words', {
				'w': sText
			}).success(function(data, tab) {
				if (data.msg == "reconnect") {
					alert('Please reconnect on pebidi');
				} else {
					chrome.tabs.executeScript(tab.id, {
						code: 'var config = ' + JSON.stringify(data)
					}, function() {
						chrome.tabs.executeScript(tab.id, {
							file: "notification.js"
						});
					});
				}
			});
		}
	}
});
