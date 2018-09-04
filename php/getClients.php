<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'openCon.php';

$resp = array();

$result = mysql_query("SELECT * FROM cliente");
if($result){
    while ($row = mysql_fetch_array($result)) {
        array_push($resp, $row);
    }
    echo json_encode($resp);
}
