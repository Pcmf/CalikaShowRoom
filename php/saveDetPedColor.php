<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'openCon.php';
$data = file_get_contents('php://input');
$dt = json_decode($data);
$parm = json_decode($dt->params);
$pedido = $parm->pedido;
$qtys = $parm->qtys;
$cor1 = $parm->cor1;
(isset($parm->cor2))? $cor2 = $parm->cor2 : $cor2 = '';
(isset($parm->e1))? $e1 = $parm->e1 : $e1 = '';
(isset($parm->e2))? $e2 = $parm->e2 : $e2 = '';
(isset($parm->e3))? $e3 = $parm->e3 : $e3 = '';

$parm = 1;
$resp = array();
//first has to check if this modelo already has lines
$result0 = mysql_query(sprintf("SELECT max(linha) AS nlinhas FROM detpedcor WHERE pedido=%s AND modelo=%s",$pedido->pedido,$pedido->modelo));
if($result0){
    $row0 = mysql_fetch_array($result0);
    if($row0['nlinhas']>0){
        $parm = $row0['nlinhas']+1;
    }
}

$query = sprintf("INSERT INTO detpedcor(pedido,modelo,linha,cor1,cor2,elem1,elem2,elem3,qtys)"
        . " VALUES(%s,%s,%s,'%s','%s','%s','%s','%s','%s')",
        $pedido->pedido,$pedido->modelo,$parm,json_encode($cor1),json_encode($cor2), json_encode($e1),
 json_encode($e2), json_encode($e3),json_encode($qtys));
$result = mysql_query($query);
if($result){
    $result1 = mysql_query(sprintf("SELECT * FROM detpedcor "
            . " WHERE pedido=%s AND modelo=%s",$pedido->pedido,$pedido->modelo));
    if($result1){
        while ($row1 = mysql_fetch_array($result1)) {
            $row1['cor1'] = json_decode($row1['cor1']);
            $row1['cor2'] = json_decode($row1['cor2']);
            $row1['elem1'] = json_decode($row1['elem1']);
            $row1['elem2'] = json_decode($row1['elem2']);
            $row1['elem3'] = json_decode($row1['elem3']);
            $row1['qtys'] = json_decode($row1['qtys']);
            array_push($resp, $row1);
        }
        echo json_encode($resp);
    }
}