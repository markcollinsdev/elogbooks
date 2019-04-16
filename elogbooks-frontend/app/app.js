(function () {
    'use strict';

    angular.module('elogbooks', [
        'ui.router',
        'elogbooks.job',
        'elogbooks.quote',
        'elogbooks.user'

    ])
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/quotes/list');
    }]);
})();