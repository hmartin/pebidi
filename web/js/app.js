'use strict';

var API_URL = '/api_dev.php/';

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
            when('/addWord/:id', {
                templateUrl: 'partials/addWord.html',
                controller: 'WordCtrl'
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
            when('/dictionary/:id', {
                templateUrl: 'partials/dictionary.html',
                controller: 'DictionnaryCtrl',
                type: 'dictionary'
            }).
            when('/group/:id', {
                templateUrl: 'partials/dictionary.html',
                controller: 'DictionnaryCtrl',
                type: 'group'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

app.config(function ($translateProvider) {
    $translateProvider.translations('en', {
        'dictionaryOf': 'It\'s the dictionary of:',
        'clickHere': 'Click here',
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
        'addWord.doATest': 'Do a test',
        'createTest': 'Create a test',
        'simpleTest': 'Simple test',
        'simpleTimeTest': 'Simple Test with time limit',
        'questionTest': 'MCQ',
        'start': 'Start!',
        'numberOfQuestion': 'Number of question',
        'knowledge': 'Knowledge',
        'fr': 'French',
        'es': 'Spanish',
        'en': 'English',
        'de': 'German'
    });
    $translateProvider.translations('fr', {
        'dictionaryOf': 'Dictionnaire de:',
        'clickHere': 'Cliquer ici',
        'slogan': 'Créer votre dictionnaire de langue perosnnel!',
        'homepage.explication1': 'Entrez juste votre email, et n\'oubliez plus ce que vous savez',
        'email.address': 'Email',
        'go': 'Go!',
        'homepage.explication2': 'PeBiDi vous permet d\'enregistrer les mots que vous avez appris',
        'welcome': 'Bienvenue',
        'addWord.newWord': 'Ajouter un nouveau mot:',
        'addWord.word': 'Mot',
        'addWord.translation': 'Traduction, définition, exemples...',
        'save': 'Ajouter!',
        'yourDictionary': 'Votre dictionnaire',
        'addWord.doATest': 'Faire un test',
        'createTest': 'Création de Test',
        'simpleTest': 'Test simple',
        'simpleTimeTest': 'Test simple avec temps limité',
        'questionTest': 'QCM',
        'start': 'Commencer!',
        'numberOfQuestion': 'Nombre de question',
        'knowledge': 'Acquis'
    });
    $translateProvider.preferredLanguage('en');
});