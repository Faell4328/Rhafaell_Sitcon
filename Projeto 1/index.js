var elementoTabela=document.getElementById("tabela-consulta");
var copiaElementoTabela=elementoTabela.innerHTML;
var ajax=new XMLHttpRequest();

document.getElementById("input-pesquisa").addEventListener("input", function(){

    if(this.value!=""){
        document.getElementById("sessao-paginador").style="display: none";
        ajax.onreadystatechange=function(){
            if(this.readyState==4){
                if(this.status==200){
                    elementoTabela.innerHTML=this.responseText;
                }
            }
        }
        
        ajax.open("POST", "index.php?p");
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("consulta="+this.value);
    }
    else{
        document.getElementById("sessao-paginador").style="";
        elementoTabela.innerHTML=copiaElementoTabela;
    }
});

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