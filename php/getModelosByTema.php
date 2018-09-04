<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$idTema = json_decode($dt->params);

$resp = array();


$query = sprintf("SELECT M.*,A.nome FROM modelo M INNER JOIN artigo A ON M.artigo = A.id WHERE tema=%s",$idTema);
        
$result = mysql_query($query);
if ($result){
    while ($row = mysql_fetch_array($result)) {
        array_push($resp,$row);
      
    }

    echo json_encode($resp);
} else {
    echo $query;
}
        
