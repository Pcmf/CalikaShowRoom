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


$query = sprintf("UPDATE detpedcor SET ".$parm->elemnum."='%s' WHERE pedido=%s AND modelo=%s AND linha=%s", json_encode($parm->element), $parm->pid, $parm->mid,$parm->linha);

$result = mysql_query($query);
if($result){
    echo 'Ok';
} else {
    echo $query;
}

