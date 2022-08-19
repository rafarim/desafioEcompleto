<?php
require '../ConexaoBanco/index.php';
$_POST = json_decode(file_get_contents('php://input'), true);
header("Content-Type: application/json");

$arrayRetorno = [];

/* Seleciona os pagamentos do banco de dados que estao em 
aguardo e serao pagas por cartao */
$query = "SELECT p.id as order_id, p.valor_total/pp.qtd_parcelas as amount,
pp.num_cartao as card_number, pp.codigo_verificacao as card_cvv,
pp.vencimento as card_expiration_date, pp.nome_portador as card_holder_name,
c.id as client_id, c.nome as client_name, c.tipo_pessoa as client_type,
c.email as client_email, c.cpf_cnpj as doc_number, c.data_nasc as birthday
FROM pedidos_pagamentos as pp
INNER JOIN pedidos as p ON pp.id_pedido = p.id
INNER JOIN clientes as c ON p.id_cliente = c.id
INNER JOIN lojas_gateway as lg ON p.id_loja = lg.id_loja
WHERE p.id_situacao = ".PEDSITUACAO['AGUARDO']."
AND pp.id_formapagto = ".FORMAPAG['CARTAO']."
AND lg.id_gateway = ".GATEWAY['PAGCOMPLETO'].";";
$resultado = pg_query($conn, $query);
if(pg_num_rows($resultado) == 0){
  echo json_encode(array("status" => false, "mensagem" => "Banco de dados não possui nenhum valor que precisa de processamento"));
  exit();
}

/* Faz as chamadas ao API por cada resultado da query e atualiza
o banco de dados conforme o necessario */
while($linha = pg_fetch_object($resultado)){
  $url = "https://api11.ecompleto.com.br/exams/processTransaction?accessToken=".$_POST['AcessTK'];

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  // Trata dados e prepara o POST request com os dados necessarios
  $tipoDocumento = '';
  if(strcmp($linha->client_type, "F") == 0){
    $tipoDocumento = 'cpf';
    $linha->client_type = 'individual';
  }
  else{
    $tipoDocumento = 'cnpj';
    $linha->client_type = 'corporation';
  }
  $dataSplit = explode('-', $linha->card_expiration_date);
  $linha->card_expiration_date = $dataSplit[1].substr($dataSplit[0], -2);
  $data = <<<DATA
  { 
    "external_order_id": $linha->order_id, 
    "amount": $linha->amount, 
    "card_number": "$linha->card_number", 
    "card_cvv": "$linha->card_cvv", 
    "card_expiration_date": "$linha->card_expiration_date", 
    "card_holder_name": "$linha->card_holder_name", 
    "customer": { 
      "external_id": $linha->client_id, 
      "name": "$linha->client_name", 
      "type": "$linha->client_type", 
      "email": "$linha->client_email", 
      "documents": [ 
        { 
        "type": "$tipoDocumento", 
        "number": "$linha->doc_number" 
        } 
      ], 
      "birthday": "$linha->birthday" 
    } 
   }
  DATA;

  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

  // Realiza o POST a API e atualiza os dados de situacao e retorno no bd
  $respPost = json_decode(curl_exec($curl));
  curl_close($curl);
 
  if(isset($respPost->error) ? $respPost->error : $respPost->Error){
    echo json_encode(array("status" => false, "mensagem" => "Falha em uma requisição a API, seu token pode estar inválido!"));
    exit();
  }

  $query = "UPDATE pedidos_pagamentos SET 
  retorno_intermediador = '$respPost->Message',
  data_processamento = '".date("Y-m-d H:i:s")."'
  WHERE id_pedido = $linha->order_id;";
  $retornoUpdate = pg_query($conn, $query);
  
  $situacaoPedido = '';
  switch($respPost->Transaction_code){
    case RETORNOAPI['APROVADO']:
      $situacaoPedido = PEDSITUACAO['PAGO'];
      break;
    case RETORNOAPI['ANALISE']:
      $situacaoPedido = PEDSITUACAO['AGUARDO'];
      break;
    case RETORNOAPI['ESTORNADO']:
    case RETORNOAPI['RECUSADORISCO']:
    case RETORNOAPI['RECUSADOSEMCRED']:
      $situacaoPedido = PEDSITUACAO['CANCELADO'];
      break;
  }

  $query = "UPDATE pedidos SET
      id_situacao = $situacaoPedido
      WHERE id = $linha->order_id;";
  $retornoUpdate = pg_query($conn, $query);
  array_push($arrayRetorno, array('id' => $linha->order_id, 
                                  'pessoa' => $linha->client_name, 
                                  'situacao' => $respPost->Message));
}
echo json_encode(array("status" => true, "mensagem" => "Sucesso! dados atualizados retornados.", "dados" => $arrayRetorno));
exit();
?>