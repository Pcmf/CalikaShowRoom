<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$parm= json_decode($dt->params);




$result = mysql_query("SELECT * FROM ".$parm->fx." WHERE id =".$parm->id);

if($result){
    $row = mysql_fetch_array($result);
    echo json_encode($row);
}
