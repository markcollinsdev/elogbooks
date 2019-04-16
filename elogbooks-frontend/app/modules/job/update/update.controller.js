(function () {
    'use strict';

    angular
        .module('elogbooks.job')
        .controller('JobUpdateController', ['$http', '$state', 'jobResponse', JobUpdateController]);

    function JobUpdateController($http, $state, jobResponse) {

        var vm = this;
        vm.users = jobResponse.users;
        vm.job = jobResponse.job;

        vm.job.user = null;

        //set the job options
        //i wouldn't usually hard code this
        vm.job.options = [
            {id: '0', name: 'Open'},
            {id: '1', name: 'Closed'}
        ];

        //this is a bit of a hack as i ran out of time to tidy the javascript
        vm.job.status = vm.job.options[vm.job.status];
        vm.update = update;

        function update() {

            vm.job.status = vm.job.status.id;

            $http.put(
                'http://localhost:8001/job/' + vm.job.id,
                vm.job
            ).then(function (response) {
                $state.go('jobs.view', {id:response.data.id});

            }, function () {
                console.log('Job Update Request Failed');
            });
        }
    }
})();

