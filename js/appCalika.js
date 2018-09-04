app = angular.module('appCalika',['ngRoute','ngResource','ui.bootstrap']);

app.config(function($routeProvider){
        $routeProvider
           // .when('/ordDet/:id',{templateUrl:'views/orderDetail.html',controller:'ordDetController'})
            .when('/list',{templateUrl:'views/orders.html',controller:'ordersController'})
            .when('/themes',{templateUrl:'views/themes.html',controller:'ordersController'})
            .when('/hist',{templateUrl:'views/historic.html',controller:'ordersHistController'})
            .when('/modelo/:id',{templateUrl:'views/modelo.html',controller:'modeloController'})
            .when('/clientDoc/:id',{templateUrl:'views/clientDoc.html',controller:'docController'})
            .otherwise({templateUrl:'views/themes.html',controller:'ordersController'});
});

 
app.controller('mainController', function($scope){
    if(window.sessionStorage.userData != undefined){
        var userData = window.sessionStorage.userData;
        $scope.userData = userData;
        $scope.cliente = JSON.parse(sessionStorage.cliente).nome;
    } else {
        window.location.replace('index.php');;
    }
});
/**
 * Login - faz o log in e tem a opção para registar 
 */
app.controller('loginController', function($scope,$http){
       //Check login
       $scope.validUser = false;
    window.sessionStorage.clear();
    $scope.error = '';

    $scope.login = function(u){
        $http({
            url:'php/checkUser.php',
            method: 'POST',
            data:{params: JSON.stringify(u)}
        }).then(function(resposta){
          if(resposta.data.aviso !== undefined){
            if(resposta.data.aviso == ""){
                $scope.aviso = "";
                window.sessionStorage.userData = resposta.data.nome;
                $scope.userData = resposta.data.nome;
                $http({
                  url:'php/getClients.php'
                }).then(function(answer){
                  $scope.clientes = answer.data;
                  $scope.validUser = true;
                  $scope.selectCliente = function(clt){
                        window.sessionStorage.cliente = JSON.stringify(clt);
                        window.location.replace('main.php');
                  }


                });

                
            } else {
                $scope.aviso = resposta.data.aviso;
            }
        } else{
            $scope.error = 'Problema na base de dados. Por favor contacte o suporte.';
        }
        });            
    }; 

});



