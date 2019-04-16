(function () {
    'use strict';

    angular
        .module('elogbooks.user')
        .controller('UserCreateController', ['$http', '$state', UserCreateController]);

    function UserCreateController($http, $state) {
        var vm = this;
        vm.user = {
            name : null,
            email : null
        };
        vm.create = create;

        function create() {

            $http.post(
                'http://localhost:8001/user',
                vm.user
            ).then(function (response) {
                $state.go('users.view', {id:response.data.id});
            }, function (response) {

                //return the error message
                vm.user = {
                    name : response['data']['name'],
                    email : response['data']['email']
                };

            });
        }
    }
})();

