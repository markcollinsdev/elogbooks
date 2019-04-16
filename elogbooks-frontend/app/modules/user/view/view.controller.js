(function () {
    'use strict';

    angular
        .module('elogbooks.user')
        .controller('UserViewController', ['userResponse', UserViewController]);

    function UserViewController(userResponse) {
        var vm = this;
        vm.user = userResponse;
    }
})();
