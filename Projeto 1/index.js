function enviarDadosParaSolicitacao(elemento){
    var nome=elemento.parentElement.parentElement.childNodes[1].childNodes[0].textContent;
    var nascimento=elemento.parentElement.parentElement.childNodes[3].childNodes[0].textContent;
    var cpf=elemento.parentElement.parentElement.childNodes[5].childNodes[0].textContent;
    var formulario=document.getElementById("prosseguir")

    document.getElementById("elemento-prosseguir-nome").value=nome;
    document.getElementById("elemento-prosseguir-dataNasc").value=nascimento;
    document.getElementById("elemento-prosseguir-cpf").value=cpf;

    formulario.submit();
}