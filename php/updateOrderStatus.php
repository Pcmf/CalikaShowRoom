<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'openCon.php';
$data = file_get_contents('php://input');
$dt = json_decode($data);
$id = json_decode($dt->id);


$query = sprintf("UPDATE pedido SET situacao=3 WHERE id=%s", $id);

$result = mysql_query($query);
if($result){
    echo 'Ok';
} else {
    echo $query;
}