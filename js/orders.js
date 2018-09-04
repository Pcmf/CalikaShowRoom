
angular.module('appCalika').controller('ordersController',function($scope,$http){
        //get Orders by client where situation is for aproval or is aproved (2,3)
    var parm = {};
    parm.cltId = JSON.parse(window.sessionStorage.cliente).id;
    parm.sts = 'open';
    $http({
        url:'php/getOrderByClient.php',
        method:'POST',
        data:{params:JSON.stringify(parm)}
    }).then(function(resp){
        $scope.pedidos = resp.data;
    }); 
    
});