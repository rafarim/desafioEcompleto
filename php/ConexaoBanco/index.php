<?php
// Definindo constantes para serem utilizadas nas queries
define('PEDSITUACAO', array(
  'AGUARDO'=>1,
  'PAGO'=>2,
  'CANCELADO'=>3
));
define('FORMAPAG', array(
  'BOLETO'=>1,
  'DEPOSITO'=>2,
  'CARTAO'=>3
));
define('GATEWAY', array(
  'PAGCOMPLETO'=>1,
  'CIELO'=>2,
  'PAGSEGURO'=>3
));

// Conexao com o banco de dados
$conn = pg_connect("host=localhost port=5432 dbname=ecompleto user=postgres password=test1234");
?>