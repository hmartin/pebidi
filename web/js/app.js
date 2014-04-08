'use strict';

var API_URL = '/api.php/';

var mainApp = angular.module('mainApp', [
  'ngRoute',
  'pascalprecht.translate',

  'mainControllers'
]);

mainApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'partials/home.html',
        controller: 'HomeCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

mainApp.config(function ($translateProvider) {
  $translateProvider.translations('en', {
        'slogan': 'Get your Personal Bilingual Dictionary',
        'homepage.explication1': 'You just have to enter your email, then you can save words you just have learn.',
        'email.address': 'Email address',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi permits you to don\'t forget all essential word'
  });
  $translateProvider.translations('fr', {
        'slogan': 'fr your Personal Bilingual Dictionary',
        'homepage.explication1': 'You just have to enter your email, then you can save words you just have learn.',
        'email.address': 'Email address',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi permits you to don\'t forget all essential word'
  });
  $translateProvider.preferredLanguage('en');
});