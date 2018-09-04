<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$parm = json_decode($dt->params);

$resp = array();
$situacao ='';
if($parm->sts=='open'){
    $situacao = " P.situacao<=3";
}
if($parm->sts == 'hist'){
    $situacao = "P.situacao > 3";
}

$query = sprintf("SELECT P.*, S.situacao AS status, C.codigo FROM pedido P "
        . " JOIN cliente C ON C.id = P.clienteID "
        . " JOIN situacao S ON P.situacao = S.id "
        . " WHERE P.clienteId = %s AND %s",$parm->cltId,$situacao);

$result = mysql_query($query);
if ($result){
    while ($row = mysql_fetch_array($result)) {
        $row['ref'] = $row['codigo'].((($row['ano'])*1000)+$row['refInterna']);
        array_push($resp, $row);
    }
    echo json_encode($resp);
} else {
    echo $query;
}
        
