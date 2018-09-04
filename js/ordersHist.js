
angular.module('appCalika').controller('ordersHistController',function($scope,$http){
    var parm ={};
    parm.cltId = JSON.parse(window.sessionStorage.cliente).id;
    parm.sts = 'hist';
        //get Orders by client
    $http({
        url:'php/getOrderByClient.php',
        method:'POST',
        data:{params:JSON.stringify(parm)}
    }).then(function(resp){
        $scope.pedidos = resp.data;
    }); 
    
});