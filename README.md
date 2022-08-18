# Desafio Ecompleto
Desafio passado pela Ecompleto de PHP utilizando a API fornecida para processar dados guardados em uma database.
## Critérios de desenvolvimento: 
* Linguagem: PHP 
* Abrangir todas as lojas que utilizam o gateway PAGCOMPLETO. 
* Somente pedidos realizados com a forma de pagamento “Cartão de crédito” e na situação “Aguardando Pagamento” devem ser processados. 
* A situação do pedido deverá ser atualizada conforme o retorno da API de transação. 
* Transações recusadas devem resultar no cancelamento do pedido. 
* O retorno da API deverá ser salvo na coluna “retorno_intermediador” da tabela “pedidos_pagamentos”. 
* Disponibilizar desenvolvimento em repositório online (Github, Bitbucket, etc).