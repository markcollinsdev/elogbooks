(function (){
    'use strict';

    angular
        .module('elogbooks.job', [])
       .config(registerRoutes);

    function registerRoutes($stateProvider) {
        $stateProvider
            .state('jobs', {
                abstract: true,
                url: '/jobs',
                template: '<ui-view/>'
            })
            .state('jobs.list', {
                url: '/list',
                controller: 'JobListController',
                controllerAs: 'vm',
                templateUrl: 'modules/job/list/list.html',
                resolve: {
                    jobCollectionResponse : function ($http) {
                        return $http({
                            url: 'http://localhost:8001/job',
                            method: "GET",
                            params: {}
                        }).then(function (response) {
                            console.log('JOB !!!!!');
                            console.dir(response);
                            return response.data;
                        }, function () {
                            console.log('Request Failed');
                        });
                    }
                }
            })
            .state('jobs.create', {
                url: '/create',
                controller: 'JobCreateController',
                controllerAs: 'vm',
                templateUrl: 'modules/job/create/create.html'
            })
            .state('jobs.view', {
                url: '/view/{id}',
                controller: 'JobViewController',
                controllerAs: 'vm',
                templateUrl: 'modules/job/view/view.html',
                resolve: {
                    jobResponse : function ($http, $stateParams) {
                        return $http({
                            url: 'http://localhost:8001/job/' + $stateParams.id,
                            method: "GET"
                        }).then(function (response) {
                            console.dir(response);
                            return response.data;
                        }, function () {
                            console.log('Request Failed');
                        });
                    }
                }
            })
            .state('jobs.edit', {
                url: '/edit/{id}',
                controller: 'JobUpdateController',
                controllerAs: 'vm',
                templateUrl: 'modules/job/update/update.html',
                resolve: {
                    jobResponse: function ($http, $stateParams) {
                        return $http({
                            url: 'http://localhost:8001/job/' + $stateParams.id,
                            method: "GET"
                        }).then(function (jobResponse) {

                            let data = {};

                            console.log('job get request for put');
                            //console.dir(response.data);
                            data.job = jobResponse.data;

                            return $http.get('http://localhost:8001/user').then(function(userResponse){
                                data.users = userResponse.data.data;
                                //console.log('here come the users');
                                //console.dir(userResponse.data);
                                console.log('data');
                                console.dir(data);
                                return data;
                            });


                            //return response.data;
                        }, function () {
                            console.log('id put Request Failed');
                        });
                    }
                }
            })
            .state('jobs.update', {
                url: '/update',
                controller: 'JobUpdateController',
                controllerAs: 'vm',
                templateUrl: 'modules/job/update/update.html'
            });
    }
})();