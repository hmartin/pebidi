'use strict';

var app = angular.module('app', [
    'ngRoute',
    'ngAnimate',
    'LocalStorageModule',
    'ngSanitize',
    'ui.bootstrap',
    'pascalprecht.translate'

]);

app.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'partials/home.html',
                controller: 'HomeCtrl'
            }).
            when('/createDic', {
                templateUrl: 'partials/createDic.html',
                controller: 'CreateDicCtrl'
            }).
            when('/dictionary/:id?', {
                templateUrl: 'partials/addWord.html',
                controller: 'AddWordCtrl'
            }).
            when('/addGroupWord', {
                templateUrl: 'partials/addGroupWord.html',
                controller: 'AddGroupWordCtrl'
            }).
            when('/createTest', {
                templateUrl: 'partials/createTest.html',
                controller: 'CreateTestCtrl'
            }).
            when('/questions', {
                templateUrl: 'partials/questions.html',
                controller: 'TestCtrl'
            }).
            when('/congrats', {
                templateUrl: 'partials/congrats.html',
                controller: 'CongratsTestCtrl'
            }).
            when('/wordList/:id', {
                templateUrl: 'partials/wordList.html',
                controller: 'WordListCtrl'
            }).
            when('/improve/:word', {
                templateUrl: 'partials/improve.html',
                controller: 'ImproveCtrl'
            }).
            when('/word/:id', {
                templateUrl: 'partials/word.html',
                controller: 'WordCtrl'
            }).
            when('/group/:id', {
                templateUrl: 'partials/dictionary.html',
                controller: 'DictionaryCtrl',
                type: 'group'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

function log(i) {
    console.log(i);
}

app.config(function ($translateProvider) {
  // TODO: security fails (pb with accent)
  //$translateProvider.useSanitizeValueStrategy('sanitize');
    
  $translateProvider.useStaticFilesLoader({
    prefix: 'translate/',
    suffix: '.json'
  });
    $translateProvider.preferredLanguage('en');
});