<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$fx = $dt->params;


$resp = array();

$result = mysql_query("SELECT * FROM ".$fx);

if($result){
    while ($row = mysql_fetch_array($result)) {
        array_push($resp, $row);
    }
    echo json_encode($resp);
}
