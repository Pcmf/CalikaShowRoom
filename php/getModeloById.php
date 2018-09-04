<?php

require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$mid = $dt->params;

$resp = array();


$result = mysql_query(sprintf("SELECT M.*,A.nome FROM modelo M INNER JOIN artigo A ON A.id = M.artigo WHERE M.id = %s",$mid));

if($result){
	$row = mysql_fetch_array($result);
        $resp['modelo'] =  $row;
}
//Get detalhe pedido
$result0 = mysql_query(sprintf("SELECT * from detalhepedido where modelo=%s", $mid));
if($result0){
	$row0 = mysql_fetch_array($result0);
        $resp['detped'] = $row0;
}


echo json_encode($resp);