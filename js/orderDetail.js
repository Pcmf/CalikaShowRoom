



angular.module('appCalika').controller('ordDetController',function($scope,$http,$routeParams){
    'use strict';
    
	$scope.pedido = JSON.parse($routeParams.id);
	//get tema information
	$http({
		url:'php/getDataFxId.php',
		method:'POST',
		data:{params:JSON.stringify({'fx':'tema','id':$scope.pedido.idTema})}
	}).then(function(answer){
		$scope.tema = answer.data;
	});

	//get models for this order
	$http({
		url:'php/getModelosByTemaCliente.php',
		method:'POST',
		data:{params:$routeParams.id}
	}).then(function(answer){
		$scope.modelos = answer.data;

	});


}); //fim do modulo
