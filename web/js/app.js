'use strict';

var API_URL = '/api_dev.php/';

var app = angular.module('app', [
  'ngRoute',
  'ngAnimate',
  'pascalprecht.translate',

]);

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
        when('/', {
            templateUrl: 'partials/home.html',
            controller: 'HomeCtrl'
        }).
        when('/addWord/:id', {
            templateUrl: 'partials/addWord.html',
            controller: 'HomeCtrl'
        }).
        when('/dictionary/:id', {
            templateUrl: 'partials/dictionary.html',
            controller: 'DictionnaryCtrl'
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
        'homepage.explication2': 'PeBiDi permits you to don\'t forget all essential word',
        'welcome': 'Welcome!',
        'addWord.newWord': 'Add a new word:',
        'addWord.word': 'Word',
        'addWord.translation': 'Translation, definition, examples...',
        'save': 'Save!',
        'yourDictionary': 'Your dictionary',
        'addWord.doATest': 'Do a test'
  });
  $translateProvider.translations('fr', {
        'slogan': 'Créer votre dictionnaire de langue perosnnel!',
        'homepage.explication1': 'Entrez juste votre email, et n\'oubliez plus ce que vous savez' ',
        'email.address': 'Email',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi vous permet d\'enregistrer les mots que vous avez appris',
        'welcome': 'Bienvenue',
      'addWord.newWord': 'Ajouter un nouveau mot:',
        'addWord.word': 'Mot',
        'addWord.translation': 'Traduction, définition, exemples...',
        'save': 'Ajouter!',
        'yourDictionary': 'Votre dictionnaire',
        'addWord.doATest': 'Faire un test'
  });
  $translateProvider.preferredLanguage('en');
});