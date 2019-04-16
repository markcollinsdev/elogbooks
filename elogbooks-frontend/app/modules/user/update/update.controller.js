(function () {
    'use strict';

    angular
        .module('elogbooks.user')
        .controller('UserUpdateController', ['$http', '$state', 'userResponse', UserUpdateController]);

    function UserUpdateController($http, $state, userResponse) {

        var vm = this;
        vm.user = userResponse;

        vm.update = update;

        function update() {

            $http.put(
                'http://localhost:8001/user/' + vm.user.id,
                vm.user
            ).then(function (response) {

               $state.go('users.view', {id:response.data.id});

            }, function (response) {
                console.log('Request Failed');
                console.dir(response);
            });
        }
    }
})();

