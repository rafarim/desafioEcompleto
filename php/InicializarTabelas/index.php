<?php
require '../ConexaoBanco/index.php';

// Deleta as tabelas se elas existirem
$query = "DROP TABLE IF EXISTS clientes;
          DROP TABLE IF EXISTS formas_pagamento;
          DROP TABLE IF EXISTS gateways;
          DROP TABLE IF EXISTS lojas_gateway;
          DROP TABLE IF EXISTS pedido_situacao;
          DROP TABLE IF EXISTS pedidos;
          DROP TABLE IF EXISTS pedidos_pagamentos;";
$resultado = pg_query($conn, $query);

// Cria as tabelas
$query = "CREATE TABLE IF NOT EXISTS clientes (
  id serial primary key,
  nome TEXT,
  cpf_cnpj VARCHAR(50),
  email TEXT,
  tipo_pessoa TEXT,
  data_nasc DATE,
  id_loja INT
);
INSERT INTO clientes VALUES
  (8796,'Emanuelly Alice Alessandra de Paula','96446953722','emanuellyalice@ecompleto.com.br','F','1988-01-18',90),
  (5789,'Renato Ryan Lopes','78891957615','renato_ryan@ecompleto.com.br','F','1947-02-08',92),
  (6748,'Kauê Bryan Souza','55782338806','kauesouza@ecompleto.com.br','F','1945-06-27',90),
  (6872,'Samuel Emanuel Castro','85673855800','samuel.castro@ecompleto.com.br','F','1988-11-05',115),
  (6716,'Raquel Nicole Moura','36118844720','raquelnicole_moura@ecompleto.com.br','F','1990-02-20',98),
  (4802,'Fernando Julio Ramos','20499776461','fernando_julio99@ecompleto.com.br','F','1999-09-11',97),
  (9484,'Kevin Yuri Pedro Lopes','95829123088','kevinpedro@ecompleto.com.br','F','1996-06-03',94),
  (1830,'Thales André Pereira','13440817709','samuel.castro@ecompleto.com.br','F','1995-04-07',90),
  (2280,'Heloisa Valentina Fabiana Moura','99386767660','heloisavalentina@ecompleto.com.br','F','1984-12-12',92);".
  "CREATE TABLE IF NOT EXISTS formas_pagamento (
    id serial primary key,
    descricao VARCHAR(50)
);
INSERT INTO formas_pagamento VALUES
    (1,'Boleto Bancário'),
    (2,'Depósito Bancário'),
    (3,'Cartão de Crédito');".
"CREATE TABLE IF NOT EXISTS gateways (
  id serial primary key,
  descricao TEXT,
  endpoint TEXT
);
INSERT INTO gateways VALUES
  (1,'PAGCOMPLETO','https://api11.ecompleto.com.br/'),
  (2,'CIELO','https://api.cielo.com.br/v1/transactions/'),
  (3,'PAGSEGURO','https://api.pagseguro.com.br/transactions/');". 
"CREATE TABLE IF NOT EXISTS lojas_gateway (
  id serial primary key,
  id_loja INT,
  id_gateway INT
);
INSERT INTO lojas_gateway VALUES
  (1,90,1),
  (2,92,2),
  (3,115,1),
  (4,98,1),
  (5,97,1),
  (6,94,1);". 
"CREATE TABLE IF NOT EXISTS pedido_situacao (
  id serial primary key,
  descricao VARCHAR(50)
);
INSERT INTO pedido_situacao VALUES
  (1,'Aguardando Pagamento'),
  (2,'Pagamento Identificado'),
  (3,'Pedido Cancelado');". 
"CREATE TABLE IF NOT EXISTS pedidos (
  id SERIAL primary key,
  valor_total NUMERIC(12, 2),
  valor_frete NUMERIC(12, 2),
  data TIMESTAMP,
  id_cliente INT,
  id_loja INT,
  id_situacao INT
);
INSERT INTO pedidos VALUES
  (98302,250.74,33.4,'2021-08-20 00:00:00',8796,90,1),
  (98303,583.92,57.85,'2021-08-23 00:00:00',5789,92,1),
  (98304,97.25,17.5,'2021-08-23 00:00:00',6748,90,2),
  (98305,66.89,22.55,'2021-08-25 00:00:00',6872,115,2),
  (98306,115.9,19.5,'2021-08-25 00:00:00',6716,98,1),
  (98307,153.72,25.5,'2021-08-25 00:00:00',4802,97,1),
  (98308,87.9,13.5,'2021-08-26 00:00:00',9484,94,1),
  (98309,223.9,28.75,'2021-08-27 00:00:00',1830,90,2),
  (98310,58.9,19.85,'2021-08-27 00:00:00',2280,92,1);". 
"CREATE TABLE IF NOT EXISTS pedidos_pagamentos (
  id SERIAL PRIMARY KEY,
  id_pedido INT,
  id_formapagto INT,
  qtd_parcelas INT,
  retorno_intermediador TEXT,
  data_processamento TEXT,
  num_cartao VARCHAR(50),
  nome_portador VARCHAR(50),
  codigo_verificacao int,
  vencimento varchar(10)
);
INSERT INTO pedidos_pagamentos VALUES
  (103013,98302,3,4,null,null,'5236387041984690','Elisa Adriana Barbosa','319','2022-08'),
  (103014,98303,3,2,null,null,'5372472213342610','Renato Ryan','848','2022-03'),
  (103015,98304,1,1,null,null,null,null,null,null),
  (103016,98305,2,1,null,null,null,null,null,null),
  (103017,98306,3,1,null,null,'4929521310619600','Raquel Moura','721','2023-03'),
  (103018,98307,3,1,null,null,'4275824466404380','Fernando Julio','482','2022-05'),
  (103019,98308,3,5,null,null,'5167913943407160','Kevin Pedro','441','2022-10'),
  (103020,98309,2,1,null,null,null,null,null,null),
  (103021,98310,1,1,null,null,null,null,null,null);";

$resultado = pg_query($conn, $query);
exit();
?>