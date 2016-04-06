'use strict';
/*global angular*/

var app = angular.module('app', [
    'ngRoute',
    'ngAnimate',
    'LocalStorageModule',
    'ngSanitize',
    'ui.bootstrap',
    'pascalprecht.translate',
    'ngFlash',
    'cfp.hotkeys',
    'angularUtils.directives.dirPagination'

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
            when('/createTest/:id/:slug?', {
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
            when('/wordList/:id/:slug?', {
                templateUrl: 'partials/wordList.html',
                controller: 'WordListCtrl'
            }).
            when('/improve/:word', {
                templateUrl: 'partials/improve.html',
                controller: 'ImproveCtrl'
            }).
            when('/word/:word', {
                templateUrl: 'partials/word.html',
                controller: 'WordCtrl'
            }).
            when('/group/list', {
                templateUrl: 'partials/group/list.html',
                controller: 'GroupListCtrl'
            }).
            when('/group/create', {
                templateUrl: 'partials/group/create.html',
                controller: 'GroupCreateCtrl'
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

app.config(function ($translateProvider) {
    $translateProvider.useSanitizeValueStrategy('sanitize');

    $translateProvider.useStaticFilesLoader({
        prefix: 'translate/',
        suffix: '.json'
    });
    $translateProvider.preferredLanguage('en');
});