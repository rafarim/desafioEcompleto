<?php
require '../ConexaoBanco/index.php';

/* Seleciona os pagamentos do banco de dados que estao em 
aguardo e serao pagas por cartao */
$query = "SELECT * FROM pedidos_pagamentos as pp
INNER JOIN pedidos as p ON pp.id_pedido = p.id
INNER JOIN lojas_gateway as lg ON p.id_loja = lg.id_loja
WHERE p.id_situacao = ".PEDSITUACAO['AGUARDO']."
AND pp.id_formapagto = ".FORMAPAG['CARTAO']."
AND lg.id_gateway = ".GATEWAY['PAGCOMPLETO'].";";
$resultado = pg_query($conn, $query);

/* Faz as chamadas ao API por cada resultado da query e atualiza
o banco de dados conforme o necessario */
while($linha = pg_fetch_object($resultado)){
  echo $linha->id."\n";
}
?>