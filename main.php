<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
--> 
<?php
    session_start();
    if( !isset($_SESSION['valid_ID']) || $_SESSION['valid_ID']==false ){
          header('Location: index.php');
          die();
    }
?>
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
        <script src="js/orders.js" type="text/javascript"></script>
        <script src="js/orderDetail.js" type="text/javascript"></script>
        <script src="js/modelo.js" type="text/javascript"></script>
        <script src="js/clientDoc.js" type="text/javascript"></script>
        <script src="js/ordersHist.js" type="text/javascript"></script>
        <link href="css/css.css" rel="stylesheet" type="text/css"/>
        

        
    </head>

    
    <body ng-controller="mainController">
        
        <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">
               <img src="../Calika/img/calikaLogo.png" class="logotipo" alt="Calika"/></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#!themes"><span class="fa fa-area-chart">&nbsp;</span>Listar Temas</a></li>
                    <li><a href="#!list"><span class="fa fa-align-justify">&nbsp;</span>Listar Pedidos</a></li>
                    <li><a href="#!hist"><span class="fa fa-archive">&nbsp;</span>Historico</a></li>

                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a><i class="fa fa-address-book"> {{cliente}}</i></a></li>
                    <li><a href="#!user"><span class="glyphicon glyphicon-user"></span> {{userData}}</a></li>
                    <li><a href="php/logout.php"><span class="glyphicon glyphicon-log-out"></span> Sair</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>

        <!--Main-->
        <main ng-view=""></main>
        <br/>
        <br/>
        <br/>
        <!--Footer-->
        <footer class="navbar navbar-fixed-bottom bg-info" style="padding-top: 15px;">
            <div class="container text-center">
                <em>Copyright <span class="fa fa-copyright"></span>
                    2018 - Calika. All rights reserved. Design by 
                    <a>Pcmf</a>
                </em>
            </div>
        </footer>
    <chasing-dots-spinner  ng-show="prograssing"></chasing-dots-spinner>
        <!--<circle-spinner ng-show="prograssing"></circle-spinner>-->
    </body>

        <script src="lib/angular-animate.min.js" type="text/javascript"></script>
        <script src="lib/angular-sanitize.min.js" type="text/javascript"></script> 


        <script src="lib/angular-spinkit.js" type="text/javascript"></script>

      
</html>


 