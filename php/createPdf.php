<?php
session_start();

require_once 'fpdf.php';
require_once 'class.phpmailer.php';
require_once 'class.smtp.php';
require_once 'openCon.php';
define('EURO',chr(128));

$data = file_get_contents("php://input");
$dt = json_decode($data);
$pid= $dt->params;

$pdf = new FPDF();
$pdf->AliasNbPages();

//Aceder aos dados do pedido - recebe o id como parametro
$query0 = sprintf("SELECT P.*, T.nome,T.descricao,T.folder,T.imagem "
        . " FROM pedido P INNER JOIN tema T ON P.idTema = T.id "
        . " WHERE P.id=%s",$pid);
$result0 = mysql_query($query0);
if($result0){
   $row0 = mysql_fetch_array($result0); 
   $query = sprintf("SELECT D.*,M.*,A.nome FROM detalhepedido D INNER JOIN modelo M ON D.modelo=M.id"
           . " INNER JOIN artigo A ON A.id=M.artigo "
           . " WHERE D.pedido=%s",$pid);
  // echo $query;
   $result = mysql_query($query);
   if($result){
       while ($row = mysql_fetch_array($result)) {
           
// Para cada modelo cria uma pagina
            $pdf->AddPage();

            //Criar cabeçalho
            $pdf->Image('../../calika/img/'.$row0['folder'].'/'.$row0['imagem'], 10,8 ,0,15);
            $pdf->Image('../../calika/img/calikaLogo.png', 160,8 ,25);
            $pdf->SetFont('Times','B',12);
            $pdf->MultiCell(0,12,'Tema: '.utf8_decode($row0['nome']),0,'C');
            $pdf->Ln(1);
            $pdf->SetFont('Times','',8);
            $pdf->MultiCell(0,8,'Modelo: '.utf8_decode($row['nome']));
            $pdf->MultiCell(0,-1,'Referencia: '.utf8_decode($row['refinterna']));
            
            //Imagem principal - Verificar se existem mais imagens
            //Gerir o tamanho de acordo com a quantidade de imagens existentes
            $imagens = json_decode($row['imagens']);
            if(sizeof($imagens)==0){
                $y = $pdf->GetY();
                $pdf->SetY($y+10);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$row['mainimg'], 10, $y+10, 0,95);
            }
            if(sizeof($imagens)==1){
                $y = $pdf->GetY();
                $pdf->SetY($y+10);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$row['mainimg'], 10, $y+10, 85,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[0], 100, $y+10, 85,0);
            }
            if(sizeof($imagens)==2){
                $y = $pdf->GetY();
                $pdf->SetY($y+10);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$row['mainimg'], 10, $y+10, 85,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[0], 100, $y+10, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[1], 150, $y+10, 45,0);
            }
            if(sizeof($imagens)==3){
                $y = $pdf->GetY();
                $pdf->SetY($y+10);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$row['mainimg'], 10, $y+10, 85,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[0], 100, $y+10, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[1], 150, $y+10, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[2], 100, $y+65, 45,0);
            } 
            if(sizeof($imagens)>=4){
                $y = $pdf->GetY();
                $pdf->SetY($y+10);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$row['mainimg'], 10, $y+10, 85,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[0], 100, $y+10, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[1], 150, $y+10, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[2], 100, $y+65, 45,0);
                $pdf->Image('../../calika/img/'.$row['pasta'].'/'.$imagens[3], 150, $y+65, 45,0);
            } 

            //Descrição - Abaixo da imagem principal
            $pdf->SetY($y+110);
            $pdf->SetFont('Times','',10);
            $pdf->MultiCell(60,8,utf8_decode('Descrição: '));
//            $pdf->Ln(1);
            $pdf->SetFont('Times','',8);
            $pdf->MultiCell(0,4,utf8_decode($row['descricao']),0,'L');
            
            $pdf->Ln(2);
            $pdf->SetFont('Times','',10);
            $pdf->MultiCell(60,8,utf8_decode('Observações: '));
//            $pdf->Ln(1);
            $pdf->SetFont('Times','',8);
            $pdf->MultiCell(0,4,utf8_decode($row['obscliente']),0,'L');          
            

            //CRIAR A TABELA - Cabeçalho
            $pdf->Ln(8);
            $tw = [15,22,8]; //Largura das colunas Cor / elem / escala
            $pdf->SetFillColor(217,237,247);
            $pdf->SetFont('', 'B');
            $pdf->Cell($tw[0], 4, 'Cor 1',1,0,'C',true);
            $pdf->Cell($tw[0], 4, 'Cor 2',1,0,'C',true);
            $pdf->Cell($tw[1], 4, 'Elem 1',1,0,'C',true);
            $pdf->Cell($tw[1], 4, 'Elem 2',1,0,'C',true);
            $pdf->Cell($tw[1], 4, 'Elem 3',1,0,'C',true);
            $query1= sprintf("SELECT tamanhos FROM escala WHERE id=%s",$row['escala']);
            $result1=mysql_query($query1);
            if($result1){
                $row1 = mysql_fetch_array($result1);                    
                $tamanhos = explode(',',$row1['tamanhos']);
                foreach ($tamanhos as $t){
                    $pdf->Cell($tw[2],4,$t,1,0,'C',true);
                }
            }
            
            //Dados para a tabela
            $pdf->SetFont('Times','',6);
            
            $query2 = sprintf("SELECT * FROM detpedcor WHERE pedido=%s AND modelo=%s",$pid,$row['id']);
            $result2 = mysql_query($query2);
            if($result2){
                $qtyTotal = 0;
                while ($row2 = mysql_fetch_array($result2)) {
                    $pdf->Ln(4);
                    //Cores
                    $cor1 = json_decode($row2['cor1']);
                    $pdf->Cell($tw[0], 4, $cor1->nome,1,0,'L');
                    $corT = json_decode($row2['cor2']);
                    (isset($corT->nome))? $cor2 = $corT->nome: $cor2 = "";
                    $pdf->Cell($tw[0], 4, $cor2,1,0,'L');
                    //Elementos
                    $elemT = json_decode($row2['elem1']);
                    (isset($elemT->elemento->nome))? $elem1 = $elemT->elemento->nome: $elem1 = "";
                    (isset($elemT->corElem->nome))? $elem1 .= '/'.$elemT->corElem->nome: null;
                    $pdf->Cell($tw[1], 4, $elem1,1,0,'L');
                    $elemT = json_decode($row2['elem2']);
                    (isset($elemT->elemento->nome))? $elem2 = $elemT->elemento->nome: $elem2 = "";
                    (isset($elemT->corElem->nome))? $elem2 .= '/'.$elemT->corElem->nome: null;
                    $pdf->Cell($tw[1], 4, $elem2,1,0,'L');
                    $elemT = json_decode($row2['elem3']);
                    (isset($elemT->elemento->nome))? $elem3 = $elemT->elemento->nome: $elem3 = "";
                    (isset($elemT->corElem->nome))? $elem3 .= '/'.$elemT->corElem->nome: null;
                    $pdf->Cell($tw[1], 4, $elem3,1,0,'L');
                    //Quantidades por tamanhos
                    $qtys = json_decode($row2['qtys']);
                    foreach ($tamanhos as $t){
                        if(isset($qtys->$t)){
                            $pdf->Cell($tw[2],4,$qtys->$t,1,0,'C');
                            $qtyTotal += $qtys->$t;
                        } else {
                           $pdf->Cell($tw[2],4,'',1,0,'C'); 
                        }
                    }
                }
              
            }
        //Tabela com os valores totais e preço
        $pdf->Ln(8);
        $pdf->SetFillColor(217,237,247);
        $pdf->SetFont('', 'B');
        $pdf->Cell(20, 4, utf8_decode('Preço Unitário'),1,0,'C',true);
        $pdf->Cell(20, 4, 'Quantidade',1,0,'C',true);
        $pdf->Cell(20, 4, 'Valor Total',1,0,'C',true);
        $pdf->Ln();
        $pdf->SetFont('', '');
        $pdf->Cell(20, 4,$row['preco'] ,1,0,'C');
        $pdf->Cell(20, 4, $qtyTotal,1,0,'C');
        $pdf->Cell(20, 4, $row['preco']*$qtyTotal.' EURO',1,0,'C');        
        
        
            
    
       } //Fim do ciclo principal - pedido/modelo
   }    

$pdf->Output();

$doc = '../DocToCliente/DocCliente_'.$row0['clienteId'].'_'.$row0['ano'].'_'.$row0['nome'].'.pdf';
$pdf->Output($doc,'F');
}


//Aceder aos dados do Cliente para obter o email
$result1 = mysql_query(sprintf("SELECT * FROM cliente WHERE id = %s",$row0['clienteId']));
if($result1){
    $rowC = mysql_fetch_array($result1);
}

//Enviar o email


$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'pedroferreira2005@gmail.com';
$mail->Password = 'pcmf@3315';
$mail->SMTPSecure = 'tls';


$mail->From =  'pedroferreira2005@gmail.com';
$mail->addAddress($rowC['email']);
$mail->addBCC('pedroferreira2005@gmail.com');


$mail->Subject = 'Pedidos Calika Baby '.date('d-m-Y');
$mail->isHTML(TRUE);
$mail->Body = '<h2>A copia do pedido segue em anexo.</h2><br/><br/>  Cumprimentos,<br/><b>  Rui Gomes</b>';
$mail->WordWrap = 50;
$path = '../DocToCliente/DocCliente_'.$row0['clienteId'].'_'.$row0['ano'].'_'.$row0['nome'].'.pdf';
$mail->addAttachment($path);


if(!$mail->send()){
    return 'Erro no envio! Mailer error: '.$mail->ErrorInfo;
} else { 
    return 'Mensagem enviada com sucesso.';
}


