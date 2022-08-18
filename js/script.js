document.getElementById("botaoPag").onclick = function() {
  fetch('/desafioEcompleto/php/ProcessarPedidos').then(res => res.json()).then(json => console.log(json));
}
document.getElementById("botaoRes").onclick = function() {
  fetch('/desafioEcompleto/php/InicializaasdrTabelas')
  .then(res => console.log(res.ok));
}