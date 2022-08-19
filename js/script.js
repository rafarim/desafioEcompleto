// Botao que processa pagamentos
document.getElementById("botaoPag").onclick = function() {
  // Manda uma requisicao POST para o arquivo PHP com o AccessToken fornecido
  fetch('/desafioEcompleto/php/ProcessarPedidos/', {
    method: "POST",
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      AcessTK: document.getElementById('inputACTK').value
    })
  })
  .then(res => res.json())
  .then(json => {
    // Limpa a tabela e o texto de resultado
    document.getElementById("tabelaResults").innerHTML = '';
    document.getElementById("mensagemResult").removeAttribute('class');
    document.getElementById("mensagemResult").classList.add('textoPadrao');
    document.getElementById("mensagemResult").innerHTML = 'Executando...';

    if(json['status']){
      // Inicia os headers da tabela
      ['ID Pagamento', 'Pessoa', 'Situação'].forEach(function(valor){
        var divNova = document.createElement("div");
            divNova.classList.add('item-grade');
            divNova.classList.add('item-headers');
            var conteudoNovo = document.createTextNode(valor);
            divNova.appendChild(conteudoNovo);
            document.getElementById("tabelaResults").append(divNova);
      });

      // Preenche a tabela com os dados recebidos, e adiciona o texto de resultado com sua coloracao
      json['dados'].forEach(function(dado){
        for(var chave in dado){
          var divNova = document.createElement("div");
          divNova.classList.add('item-grade');
          var conteudoNovo = document.createTextNode(dado[chave]);
          divNova.appendChild(conteudoNovo);
          document.getElementById("tabelaResults").append(divNova);
        };
      });
      document.getElementById("mensagemResult").classList.add('textoRBom');
    } else{
      document.getElementById("mensagemResult").classList.add('textoRRuim');
    }
    document.getElementById("mensagemResult").innerHTML = json['mensagem'];
  });
}

// Botao para (re)iniciar banco de dados
document.getElementById("botaoRes").onclick = function() {
  fetch('/desafioEcompleto/php/InicializarTabelas/')
  .then(res => res.json())
  .then(json => {
    // Limpa o texto de resultado
    document.getElementById("mensagemResult").removeAttribute('class');
    document.getElementById("mensagemResult").classList.add('textoPadrao');
    document.getElementById("mensagemResult").innerHTML = 'Executando...';

    // Muda a coloracao do texto de maneira apropriada e adiciona a mensagem de resultado
    if(json['status']){
      document.getElementById("mensagemResult").classList.add('textoRBom');
    } else{
      document.getElementById("mensagemResult").classList.add('textoRRuim');
    }
    document.getElementById("mensagemResult").innerHTML = json['mensagem'];
  });;
}