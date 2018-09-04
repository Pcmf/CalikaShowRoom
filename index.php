<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html ng-app="appCalika">
    <head>
        <meta charset="UTF-8">
        <title>Show Room - Calika</title>
        <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <script src="lib/jquery.min.js" type="text/javascript"></script>
        <link href="lib/bootstrap.3.3.7/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="lib/bootstrap.toggle.2.2.0/bootstrap-toggle.css" rel="stylesheet" type="text/css"/>
        <script src="lib/bootstrap3.3.6/bootstrap.min.js" type="text/javascript"></script>
        <link href="lib/fontAwesome.4.7.0/font-awesome.css" rel="stylesheet" type="text/css"/>
        <script src="lib/angular.1.6.6.min.js" type="text/javascript"></script>
        <script src="lib/angularjs-1.6.6-angular-route.js" type="text/javascript"></script>
        <script src="lib/angular-file-upload.js" type="text/javascript"></script>
        <script src="lib/angular-resource.js" type="text/javascript"></script>

        <!--a linha a baixo é utilizada para mostrar o modal-->
        <link href="lib/bootstrap.3.3.7/uibootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.12.1.js"></script> 
        
        
        <script src="js/appCalika.js" type="text/javascript"></script>
        <link href="css/css.css" rel="stylesheet" type="text/css"/>
    </head>
    <body ng-controller="loginController">
        <div class="container text-center">
            <div class="imagemCentral">
                <img src="../Calika/img/header.png" alt="logotipo">
            </div>
            <br/><br/><br/><br/>
            <div class="row">
            <div class="col-xs-1 col-sm-4">&nbsp;</div>
            <div class="container col-xs-12 col-sm-4" style="margin: auto">
                <div class="well well-lg">
                    <form >
                          <div class="col-xs-12 text-center">
                          <label class="col-xs-5 text-right text-primary" for="userName">Utilizador: </label>
                          <div class="form-group col-xs-4">
                              <input style="min-width:100px" type="text" class="form-control" ng-model="u.userName" placeholder="utilizador" required="true"/>
                          </div>
                          <div class="col-xs-3">&nbsp;</div>
                          </div>
                          <br/><br/>
                          <div class="col-xs-12">
                          <label class="col-xs-5 text-right text-primary"  for="pwd">Password:</label>
                          <div class="form-group col-xs-4">
                              <input style="min-width:100px" type="password" class="form-control" ng-model="u.pwd" placeholder="senha" required="true"/>
                          </div>
                          <div class="col-xs-3">&nbsp;</div>
                          </div>
                        
                        <div class="row text-center" ng-show="!validUser">
                        <button type="submit" ng-click="login(u)"  class="btn btn-success btn-lg">Validar</button>
                            <h4 class="text-danger">{{error}}</h4>
                        </div>
                    </form>
                    <div class="text-center" ng-show="validUser">
                        <form>
                            <div class="form-group text-center" >
                                <label class="text-primary">Selecione o Cliente</label>
                                <select style="width: 160px;" class="form-control" ng-model="clt" ng-options="clt.nome for clt in clientes track by clt.id" ng-change="selectCliente(clt)">
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-1 col-sm-4">&nbsp;</div>
            </div>



        </div>


        
        <!--Footer-->
        <footer class="navbar navbar-fixed-bottom bg-info" style="padding-top: 15px;">
            <div class="container text-center">
                <em>Copyright <span class="fa fa-copyright"></span>
                    2017 - Calika Baby. All rights reserved. Design by 
                    <a>Pcmf</a>
                </em>
            </div>
        </footer>
    </body>
</html>
