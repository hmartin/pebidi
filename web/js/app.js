'use strict';

var API_URL = '/api_dev.php/';

var app = angular.module('app', [
    'ngRoute',
    'ngAnimate',
    'ngCookies',
    'pascalprecht.translate',

]);

app.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'partials/home.html',
                controller: 'HomeCtrl'
            }).
            when('/addWord/:id', {
                templateUrl: 'partials/addWord.html',
                controller: 'HomeCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

app.config(function ($translateProvider) {
    $translateProvider.translations('en', {
        'slogan': 'Get your Personal Bilingual Dictionary',
        'homepage.explication1': 'You just have to enter your email, then you can save words you just have learn.',
        'email.address': 'Email address',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi permits you to don\'t forget all essential word'
    });
    $translateProvider.translations('fr', {
        'slogan': 'Créer votre dictionnaire de langue perosnnel!',
        'homepage.explication1': 'Entrez juste votre email, et n\'oubliez plus ce que vous savez',
        'email.address': 'Email',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi vous permet d\'enregistrer les mots que vous avez appris'
    });
    $translateProvider.preferredLanguage('en');
});