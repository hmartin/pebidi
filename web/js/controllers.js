

app.controller('HomeCtrl', function($scope, $http, $location, $translate, dictionaryService) {
  
        $scope.processForm = function() {
            $http.post(API_URL + 'emails/onlies.json', $scope.formData).success(function(data) {
                                        
                if(data.id) {
                    dictionaryService.set(data)               
                    $location.path('/addWord/'+data.id);
                }
            });
        };
});

app.controller('langCtrl', function($scope, $http, $location, $translate, dictionaryService) {
                            
  $scope.changeLanguage = function (key) {
      alert('ijoj');
    $translate.use(key);
  };
});