angular.module('appCalika').controller('modeloController',function($scope,$http,$routeParams,$modal,$timeout){
	//load modelo 
	var slides= [];
      //Receives the order/theme id and with that goes get all models main data (id,mainimg,ref)
      var temaId = $routeParams.id;
      //Get theme data
      $http({
          url:'php/getDataFxId.php',
          method:'POST',
          data:{params:JSON.stringify({'fx':'tema','id':temaId})}
      }).then(function(answer){
          $scope.tema = answer.data;
      });
      //Get all models form this theme Id
      $http({
          url:'php/getModelosByTema.php',
          method:'POST',
          data:{params:$routeParams.id}
      }).then(function(answer){
          $scope.modelsPics = answer.data;
        //get model information
          getModelo(answer.data[0].id);
        $timeout(function(){
           setQtyTotal(); 
        },1000);
        });
    //Change model
    $scope.changeModel = function(mId){
        getModelo(mId);
        $timeout(function(){
           setQtyTotal(); 
        },1000);
        
    };
// change big picture
    $scope.changeBigPic = function(pic){
        $scope.bigPic = pic.url;
    };
//Show modal with BigPicture
    $scope.showBigPic = function(pic){
        var modalInstance = $modal.open({
            templateUrl:'modalShowBigPic.html',
            controller: 'modalInstanceShowBigPic',
            size : 'lg',
            resolve:{ items: function(){
                    return pic;
                }
            }
        });
    };
//Button to add select/add colors and elements /qty
    $scope.addColorsnElem = function(){
        var parm = {};
        parm.modelo = $scope.modelo;
        parm.detPed = $scope.detPed;
        var modalInstance = $modal.open({
            templateUrl: 'modalAddColorsnElems.html',
            controller: 'modalInstanceAddcolorsnElem',
            size: 'lg',
            resolve: {items: function () {
                    return parm;
                }
            }
        });
        //data return from modalInstance -  by sizes Object
        modalInstance.result.then(function(result) {
            showDetColorsQty(); 
        });
    };
    
    //Button to remove Color / elements / qty line
    $scope.removeColorElemQty = function(ln){
        $http({
            url:'php/removeColorElemQty.php',
            method:'POST',
            data:{params:JSON.stringify(ln)}
        }).then(function(){
            showDetColorsQty();
        });
    };
    
    //Funtion to update Qty 
    $scope.updateQty = function(line){
        setQtyTotal();
        //save to DB
        $http({
            url:'php/updateQtyByModel.php',
            method:'POST',
            data:{params:JSON.stringify(line)}
        });
    };
    //Function to edit Colors - Opens modal
    $scope.editColor =function(line,cornum){       
        var modalInstance = $modal.open({
            templateUrl:'modalEditColor.html',
            controller:'modalInstanceEditColor',
            size: 'sm',
            resolve:{items:function(){
                    return line[cornum];
                }
            }
        });
        //data return from modalInstance -  by sizes Object
        modalInstance.result.then(function(result) {
            var parm = {};
            parm.mid = line.modelo;
            parm.pid = line.pedido;
            parm.linha = line.linha;
            parm.cor = result;
            parm.cornum = cornum;
            $http({
                url:'php/updateColorByModel.php',
                method:'POST',
                data:{params:JSON.stringify(parm)}
            }).then(function(){
                showDetColorsQty();
            });
        });
    };
     //Function to edit Colors - Opens modal
    $scope.editElement =function(line,elemnum){
        var modalInstance = $modal.open({
            templateUrl:'modalEditElement.html',
            controller:'modalInstanceEditElement',
            size: 'lg',
            resolve:{items:function(){
                    return line[elemnum];
                }
            }
        });
        //data return from modalInstance -  Object
        modalInstance.result.then(function(result) {
            var parm = {};
            parm.mid = line.modelo;
            parm.pid = line.pedido;
            parm.linha = line.linha;
            parm.element = result;
            parm.elemnum = elemnum;
            $http({
                url:'php/updateElementByModel.php',
                method:'POST',
                data:{params:JSON.stringify(parm)}
            }).then(function(){
                showDetColorsQty();
            });
            
        });
    };
    //Function to update prices
    $scope.updatePrice = function(preco,mid){
        var parm={};
        parm.newPrice = preco;
        parm.mid = mid;
        $http({
            url:'php/updatePriceByModel.php',
            method:'POST',
            data:{params:JSON.stringify(parm)}
        });
    };
    //Function to Save Update Observations
    $scope.saveObs = function(){
      var parm ={};
      parm.mid = $scope.modelo.id;
      parm.obsClient = $scope.modelo.obscliente;
      $http({
          url:'php/updateObsByModel.php',
          method:'POST',
          data:{params:JSON.stringify(parm)}
      });
    };
    //Finalize Order
    $scope.finalize = function(){
        //Update order status to 3 (aproved)

        $http({
            url:'php/updateOrderStatus.php',
            method:'POST',
            data:{id:$scope.detPed.pedido}
        });
        
        //Open modal to ask if want to send PDF to client
        var modalInstance = $modal.open({
            templateUrl: 'modalPDF.html',
            controller: 'modalInstancePDF',
            size: 'sm',
            resolve:{items:function(){
                    return $scope.detPed.pedido;
                }
            }
        });
        //data return from modalInstance -  by sizes Object
        modalInstance.result.then(function() {
               window.location.replace('#list'); 
        });
    };
        

    

function getModelo(id){
    $http({
		url:'php/getModeloById.php',
		method:'POST',
		data:{params:id}
	}).then(function(answer){
                var slides =[];
		slides[0] = {'name':'','url':'../Calika/img/modelos/'+answer.data.modelo.mainimg};
		if(answer.data.modelo.imagens != ""){
		var Things=JSON.parse(answer.data.modelo.imagens);
			for (var i = 0; i<Things.length ;  i++) {
				slides[i+1] = {'name':'','url':'../Calika/img/modelos/'+Things[i]};
			}
		}
                $scope.bigPic = slides[0].url;
                $scope.slides = slides;
                $scope.modelo = answer.data.modelo;
                $scope.detPed = answer.data.detped;
                $scope.priceUnit = answer.data.modelo.preco;
                //Get details by color
                showDetColorsQty();
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
        $scope.detLines = answer.data; 
        setQtyTotal();    
    });
};


function setQtyTotal(){
    var qtyTotal = 0;
        $scope.detLines.forEach(function(ln){
            $scope.escala.forEach(function(esc){
                if(ln.qtys!==null && ln.qtys[esc] !== undefined && ln.qtys[esc]!=='' && ln.qtys[esc]!==null) {
                    qtyTotal += parseInt(ln.qtys[esc]);
                }
            });
        });
      $scope.qtyTotal = qtyTotal;  
};

}); //Fim do modulo


/**
 * Modal instance to select/add colors, elements and sizes
 */
angular.module('appCalika').controller('modalInstanceShowBigPic', function($scope,$modalInstance,items){
    $scope.imagem = items;

    $scope.closeModal = function(){
        $modalInstance.dismiss('Cancel');
    };
});
/**
 * Modal instance to select/add colors, elements and sizes
 */
angular.module('appCalika').controller('modalInstanceAddcolorsnElem', function($scope,$http,$modalInstance,items){
    $scope.modelo = items.modelo;
    $scope.detPed = items.detPed;
    var elmOrg = ['e3','e2','e1'];
    var elmStatus =[];
    $scope.tm = {};
    // Right Side - Selections
    $scope.showColorElem = false;
    //get colors
    $http({
    	url:'php/getData.php',
    	method:'POST',
    	data:{params:'cor'}
    }).then(function(answer){
    	$scope.cores = answer.data;
    });
    //get elements
    $http({
    	url:'php/getData.php',
    	method:'POST',
    	data:{params:'elemento'}
    }).then(function(answer){
    	$scope.elementos = answer.data;
    });
    //Remover elemento - para corregir
    $scope.removeElem = function(elem){
    	switch(elem){
            case 'e1': $scope.e1 = '';
                elmStatus.splice(elmStatus.indexOf('e1'));
                elmOrg.push('e1');
                break;
            case 'e2': $scope.e2 = '';
                elmStatus.splice(elmStatus.indexOf('e2'));
                elmOrg.push('e2')
                break;
            case 'e3': $scope.e3 = '';
                elmStatus.splice(elmStatus.indexOf('e3'));
                elmOrg.push('e3');
                break;    
        }
        //TODO - rotina para reorganizar o array original
    };
    //button to add a new element to DOM 
    $scope.addNewElement = function(){
        if(elmOrg.length>0){
            elmStatus.push(elmOrg.pop());
            $scope.elmShow = elmStatus;
        } else {
            alert("Não é possivél adicionar mais elementos");
        }
    }
       
    //Save Line Color/Elements
    $scope.saveLine = function(qtys){
            var parm = {};
            parm.pedido = $scope.detPed;
            parm.qtys = qtys;  
            parm.cor1 = $scope.cor1;
            if($scope.cor2 !== undefined && $scope.cor2) parm.cor2 = $scope.cor2;
            if($scope.e1 !== undefined) parm.e1 = $scope.e1; //elements e# are objects
            if($scope.e2 !== undefined) parm.e2 = $scope.e2;  //they have 'elemento' and 'corElem'
            if($scope.e3 !== undefined) parm.e3 = $scope.e3;
            $http({
                url:'php/saveDetPedColor.php',
                method:'POST',
                data:{params:JSON.stringify(parm)}
            }).then(function(answer){
                //if all ok the show table line
               // alert(answer.data);
               $modalInstance.close(answer.data); 
            });
    };
    
    $scope.closeModal = function(){
        $modalInstance.dismiss('Cancel');
    };

});

/**
 * Modal instance to edit colors
 */
angular.module('appCalika').controller('modalInstanceEditColor', function($scope,$http,$modalInstance,items){
    $scope.cor = items;
    //get colors
    $http({
    	url:'php/getData.php',
    	method:'POST',
    	data:{params:'cor'}
    }).then(function(answer){
    	$scope.cores = answer.data;
    });
    $scope.removeColor = function(){
        $scope.cor = {};
    }
    $scope.saveColor = function(cor){
        $modalInstance.close(cor);
    }
    
    $scope.closeModal = function(){
        $modalInstance.dismiss('Cancel');
    };
});

/**
 * Modal instance to edit elements and respective color
 */
angular.module('appCalika').controller('modalInstanceEditElement', function($scope,$http,$modalInstance,items){
    $scope.e ={};
    $scope.e.elemento = items.elemento;
    $scope.e.corElem = items.corElem;
    //get elements
    $http({
    	url:'php/getData.php',
    	method:'POST',
    	data:{params:'elemento'}
    }).then(function(answer){
    	$scope.elementos = answer.data;
    });
    //get colors
    $http({
    	url:'php/getData.php',
    	method:'POST',
    	data:{params:'cor'}
    }).then(function(answer){
    	$scope.cores = answer.data;
    });
    $scope.removeElem = function(){
        $scope.e ={};
    };
    
    //Save changes
    $scope.saveElement = function(e){
        $modalInstance.close(e);
    };
  
    $scope.closeModal = function(){
        $modalInstance.dismiss('Cancel');
    };
});

/**
 * Modal instance to ask if want send PDF to client
 */
angular.module('appCalika').controller('modalInstancePDF', function($scope,$modalInstance,items,$http){
    $scope.sendPDFtoClient = function(){
        $http({
            url:'php/createPdf.php',
            method:'POST',
            data:{params:items}
        });
        $modalInstance.close('send');
    };
    $scope.dontSend = function(){
        $modalInstance.close('dont');
    };    
    $scope.closeModal = function(){
        $modalInstance.dismiss('Cancel');
    };
});