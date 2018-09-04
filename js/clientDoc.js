/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module('appCalika').controller('docController',function($scope,$http,$routeParams,$modal,$timeout){
    
      //Receives the order/theme id and with that goes get all models main data (id,mainimg,ref)
      $http({
          url:'php/getModelosByTema.php',
          method:'POST',
          data:{params:$routeParams.id}
      }).then(function(answer){
          $scope.modelsPics = answer.data;
        //get model information
          getModelo(answer.data[1].id);
        });
 //Button to print
    $scope.imprimir = function(){
        var element = document.getElementById('element-to-print');
        html2pdf(element, {
            margin : 1,
            filename : 'folhaCliente.pdf',
            image : {type:'jpeg', quality: 0.98},
            html2canvas : { dpi:192, letterRendering:true},
            jsPDF : {unit: 'mm', format: 'A4', orientation:'portrait'}
        });
    }

function getModelo(id){
    $http({
		url:'php/getModeloById.php',
		method:'POST',
		data:{params:id}
	}).then(function(answer){
                $scope.modelo = answer.data.modelo;
                $scope.detPed = answer.data.detped;
                $scope.priceUnit = answer.data.modelo.preco;

                //get scale sizes
                $http({
                    url:'php/getDataFxId.php',
                    method:'POST',
                    data:{params: JSON.stringify({'fx':'escala','id':$scope.modelo.escala})}
                }).then(function(answer){
                    //create scala array
                    $scope.escalaName = answer.data.nome;
                    $scope.escala = answer.data.tamanhos.split(",");                
                });    
                //Get details by color
                showDetColorsQty();
            }); 
}

function showDetColorsQty(){
   // var qtyTotal = 0;
    //Get details by color
    $http({
        url:'php/getDetPedCor.php',
        method:'POST',
        data:{params:JSON.stringify($scope.detPed)}
    }).then(function(answer){
        var detLines = answer.data; 
        var qtyTotal = 0;
        detLines.forEach(function(ln){
            $scope.escala.forEach(function(esc){
                if(ln.qtys!==null && ln.qtys[esc] !== undefined && ln.qtys[esc]!=='' && ln.qtys[esc]!==null) {
                    qtyTotal += parseInt(ln.qtys[esc]);
                }
            });
        });
        $scope.detLines = detLines;
        $scope.qtyTotal = qtyTotal;     
    });
};
 
 
function getQtyTotal(){

};
 
});
