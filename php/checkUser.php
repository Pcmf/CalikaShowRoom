<?php session_start();

require_once 'openCon.php';
$data = file_get_contents("php://input");
$dt = json_decode($data);
$user = json_decode($dt->params);

$resp = array();

$result = mysql_query(sprintf("SELECT * FROM users WHERE username='%s' AND password='%s' ",$user->userName, $user->pwd));

if($result){
	$row = mysql_fetch_array($result);
	if($row['id']>0){
 		$_SESSION['valid_ID'] = true;
		$resp['nome'] = $row['nome'];
		$resp['aviso'] = "";
	} else{
		$resp['aviso']  = 'Erro no utilizador ou na password!\n Verifique e tente outra vez.';
	}
} else {
	$resp['aviso']  = 'Erro no utilizador ou na password!\n Verifique e tente outra vez.';
}

echo json_encode($resp);