
var controleDisplayProfissional=false;
var controleDisplayTipoSolicitacao=false;
var controleDisplayProcedimentos=false;
var controleAnimacao=false;

function verificarElementosAberto(){
    if(controleDisplayProfissional==true){
        document.getElementById("campo-informacao-profissional").click();
    }
    if(controleDisplayTipoSolicitacao==true){
        document.getElementById("campo-informacao-tipo-solicitacao").click();
    }
    if(controleDisplayProcedimentos==true){
        document.getElementById("campo-informacao-procedimentos").click();
    }
}

document.getElementsByClassName("elemento-informacao-paciente")[0].addEventListener("click", verificarElementosAberto);
document.getElementsByClassName("elemento-informacao-paciente")[1].addEventListener("click", verificarElementosAberto);
document.getElementsByClassName("elemento-informacao-paciente")[2].addEventListener("click", verificarElementosAberto);
document.getElementsByClassName("campo-solicitacao-dados")[3].addEventListener("click", verificarElementosAberto);
document.getElementsByClassName("campo-solicitacao-dados")[4].addEventListener("click", verificarElementosAberto);

var numeroAnteriorData=0;
document.getElementsByClassName("elemento-informacao-paciente")[1].addEventListener("input", function(){
    var conteudoTratado=this.value;
    
    while(conteudoTratado.indexOf("/")>=0){
        conteudoTratado=conteudoTratado.replace("/", "");
    }
    
    if(conteudoTratado.length==5 && numeroAnteriorData<conteudoTratado.length){
        this.value=conteudoTratado[0]+conteudoTratado[1]+"/"+conteudoTratado[2]+conteudoTratado[3]+"/"+conteudoTratado[4];
    }
    else if(conteudoTratado.length==3 && numeroAnteriorData<conteudoTratado.length){
        this.value=conteudoTratado[0]+conteudoTratado[1]+"/"+conteudoTratado[2];
    }
});

var numeroAnteriorCpf=0;
document.getElementsByClassName("elemento-informacao-paciente")[2].addEventListener("input", function(){
    var conteudoTratado=this.value;
    
    while(conteudoTratado.indexOf(".")>=0){
        conteudoTratado=conteudoTratado.replace(".", "");
    }

    while(conteudoTratado.indexOf("-")>=0){
        conteudoTratado=conteudoTratado.replace("-", "");
    }

    if(conteudoTratado.length==10 && numeroAnteriorCpf<conteudoTratado.length){
        this.value=conteudoTratado[0]+conteudoTratado[1]+conteudoTratado[2]+"."+conteudoTratado[3]+conteudoTratado[4]+conteudoTratado[5]+"."+conteudoTratado[6]+conteudoTratado[7]+conteudoTratado[8]+"-"+conteudoTratado[9];
    }
    if(conteudoTratado.length==7 && numeroAnteriorCpf<conteudoTratado.length){
        this.value=conteudoTratado[0]+conteudoTratado[1]+conteudoTratado[2]+"."+conteudoTratado[3]+conteudoTratado[4]+conteudoTratado[5]+"."+conteudoTratado[6];
    }
    if(conteudoTratado.length==4 && numeroAnteriorCpf<conteudoTratado.length){
        this.value=conteudoTratado[0]+conteudoTratado[1]+conteudoTratado[2]+"."+conteudoTratado[3];
    }
    numeroAnteriorCpf=conteudoTratado.length
});

var elementosOpcoesProfissional=document.getElementsByClassName("campo-grupo-opcoes-informacao-profissional");
for(var contadorFor=0; contadorFor<elementosOpcoesProfissional.length; contadorFor++){
    (function (contador){
        elementosOpcoesProfissional[contador].addEventListener("click", ()=>{
            document.getElementsByClassName("elementos-opcoes-profissional")[contador].click();
        });
    })(contadorFor);
}

var elementosOpcoesTipoSolicitacao=document.getElementsByClassName("campo-grupo-opcoes-informacao-tipo-solicitacao");
for(var contadorFor=0; contadorFor<elementosOpcoesTipoSolicitacao.length; contadorFor++){
    (function (contador){
        elementosOpcoesTipoSolicitacao[contador].addEventListener("click", ()=>{
            document.getElementsByClassName("elementos-opcoes-tipo-solicitacao")[contador].click();
        });
    })(contadorFor);
}

document.getElementById("campo-informacao-profissional").addEventListener("click", ()=>{
    if(controleDisplayTipoSolicitacao==true){
        document.getElementById("campo-informacao-tipo-solicitacao").click();
    }
    if(controleDisplayProcedimentos==true){
        document.getElementById("campo-informacao-procedimentos").click();
    }

    if(controleDisplayProfissional==false){
        controleDisplayProfissional=true;
        document.getElementById("campo-opcoes-informacao-profissional").style="display: block";
    }
    else{
        controleDisplayProfissional=false;
        document.getElementById("campo-opcoes-informacao-profissional").style="display: none";
    
        profissionalSelecionado=document.querySelector(".elementos-opcoes-profissional:checked");
        if(profissionalSelecionado!=null){
            profissionalSelecionado=profissionalSelecionado.parentElement.childNodes[3].innerHTML;
            document.getElementById("texto-informacao-profissional").innerHTML=profissionalSelecionado;
        }
    }
});

var TipoSolicitacaoSelecionado="";
document.getElementById("campo-informacao-tipo-solicitacao").addEventListener("click", ()=>{

    if(controleDisplayProfissional==true){
        document.getElementById("campo-informacao-profissional").click();
    }
    if(controleDisplayProcedimentos==true){
        document.getElementById("campo-informacao-procedimentos").click();
    }
    
    if(controleDisplayTipoSolicitacao==false){
        controleDisplayTipoSolicitacao=true;
        document.getElementById("campo-opcoes-informacao-tipo-solicitacao").style="display: block";
    }
    else{
        controleDisplayTipoSolicitacao=false;
        document.getElementById("campo-opcoes-informacao-tipo-solicitacao").style="display: none";

        TipoSolicitacaoSelecionado=document.querySelector(".elementos-opcoes-tipo-solicitacao:checked");
        if(TipoSolicitacaoSelecionado!=null){
            TipoSolicitacaoSelecionado=TipoSolicitacaoSelecionado.parentElement.childNodes[3].innerHTML;
            document.getElementById("texto-informacao-tipo-solicitacao").innerHTML=TipoSolicitacaoSelecionado;

            carregarProcedimentos();
        }
    }
});

var escolhaAntigaTipoSolicitacaoSelecionado="";
function carregarProcedimentos(){
    if(TipoSolicitacaoSelecionado==escolhaAntigaTipoSolicitacaoSelecionado){
        return;
    }
    else if(TipoSolicitacaoSelecionado=="Exames Laboratório"){
        document.getElementById("campo-opcoes-informacao-procedimentos").innerHTML='\n                        <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 1">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 1</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 2">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 2</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 3">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 3</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 4">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 4</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 5">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 5</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" procname="nomeProcedimentos"edimentos value="Exemplo de Exame Laboratorio 6">\n                            <label class="texto-informacao-opcoes">Exemplo de Exame Laboratorio 6</label>\n                        </div>\n                    ';
        document.getElementById("texto-informacao-procedimentos").innerHTML="Selecione";
        escolhaAntigaTipoSolicitacaoSelecionado=TipoSolicitacaoSelecionado;

        var elementosOpcoesProcedimentos=document.getElementsByClassName("campo-grupo-opcoes-informacao-procedimentos");
        for(var contadorFor=0; contadorFor<elementosOpcoesProcedimentos.length; contadorFor++){
            (function (contador){
                elementosOpcoesProcedimentos[contador].addEventListener("click", ()=>{
                    document.getElementsByClassName("elementos-opcoes-informacao-procedimentos")[contador].click();
                });
            })(contadorFor);
        }
    }
    else{
        document.getElementById("campo-opcoes-informacao-procedimentos").innerHTML='\n                        <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 1">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 1</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 2">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 2</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 3">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 3</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 4">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 4</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 5">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 5</label>\n                        </div>\n                       <div class="campo-grupo-opcoes-informacao-procedimentos">\n                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" procname="nomeProcedimentos"edimentos value="Exemplo de Consulta 6">\n                            <label class="texto-informacao-opcoes">Exemplo de Consulta 6</label>\n                        </div>\n                    ';
        document.getElementById("texto-informacao-procedimentos").innerHTML="Selecione";
        escolhaAntigaTipoSolicitacaoSelecionado=TipoSolicitacaoSelecionado;

        var elementosOpcoesProcedimentos=document.getElementsByClassName("campo-grupo-opcoes-informacao-procedimentos");
        for(var contadorFor=0; contadorFor<elementosOpcoesProcedimentos.length; contadorFor++){
            (function (contador){
                elementosOpcoesProcedimentos[contador].addEventListener("click", ()=>{
                    document.getElementsByClassName("elementos-opcoes-informacao-procedimentos")[contador].click();
                });
            })(contadorFor);
        }
    }
}

var procedimentosSelecionado=""
var copiaProcedimentosSelecionado=""
document.getElementById("campo-informacao-procedimentos").addEventListener("click", ()=>{
    
    if(controleDisplayProfissional==true  && document.querySelector(".elementos-opcoes-tipo-solicitacao:checked")!=null){
        document.getElementById("campo-informacao-profissional").click();
    }
    if(controleDisplayTipoSolicitacao==true && document.querySelector(".elementos-opcoes-tipo-solicitacao:checked")!=null){
        document.getElementById("campo-informacao-tipo-solicitacao").click();
    }

    if(controleDisplayProcedimentos==false && document.querySelector(".elementos-opcoes-tipo-solicitacao:checked")!=null){
        controleDisplayProcedimentos=true;
        document.getElementById("campo-opcoes-informacao-procedimentos").style="display: block";
    }
    else if(document.querySelector(".elementos-opcoes-tipo-solicitacao:checked")!=null){
        controleDisplayProcedimentos=false;
        document.getElementById("campo-opcoes-informacao-procedimentos").style="display: none";

        procedimentosSelecionado=document.querySelectorAll(".elementos-opcoes-informacao-procedimentos:checked");
        if(procedimentosSelecionado.length>0){
            for(contadorFor=0; contadorFor<procedimentosSelecionado.length; contadorFor++){
                if(contadorFor+1==procedimentosSelecionado.length){
                    copiaProcedimentosSelecionado+=procedimentosSelecionado[contadorFor].parentElement.childNodes[3].innerHTML;
                }
                else{
                    copiaProcedimentosSelecionado+=procedimentosSelecionado[contadorFor].parentElement.childNodes[3].innerHTML+", ";
                }
            }
            if(copiaProcedimentosSelecionado.length>52){
                procedimentosSelecionado=copiaProcedimentosSelecionado.slice(0, 55)+"...";
            }
            else{
                procedimentosSelecionado=copiaProcedimentosSelecionado;
            }
            copiaProcedimentosSelecionado="";
            document.getElementById("texto-informacao-procedimentos").innerHTML=procedimentosSelecionado;
        }
    }
    else{
        if(controleAnimacao==false){
            controleAnimacao=true;
            document.getElementById("campo-informacao-tipo-solicitacao").style="animation: campo-nao-preenchido 5s linear 0s 1 forwards;";
            document.getElementById("campo-mensagem").style="animation: campo-mensagem-erro 5s linear 0s 1 forwards;";
            document.getElementById("texto-mensagem").innerHTML="Por favor, preencha o campo \"Tipos de Solicitação\"";
            setTimeout(function(){
                document.getElementById("campo-informacao-tipo-solicitacao").style="";
                document.getElementById("campo-mensagem").style="";
                document.getElementById("texto-mensagem").innerHTML="Os campos com * devem ser preechidos obrigatóriamente.";
                controleAnimacao=false;
            }, 5000);
        }
    }
});

var camposFaltandoPreencher=[];
var contadorCamposFaltandoPreencher=0;
function verificarCamposPreenchidos(){
    if(controleAnimacao==false){
        camposFaltandoPreencher=[];
        contadorCamposFaltandoPreencher=0
        if(document.getElementsByClassName("elemento-informacao-paciente")[0].value==""){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementsByClassName("elemento-informacao-paciente")[0];
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementsByClassName("elemento-informacao-paciente")[1].value==""){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementsByClassName("elemento-informacao-paciente")[1];
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementsByClassName("elemento-informacao-paciente")[2].value==""){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementsByClassName("elemento-informacao-paciente")[2];
            contadorCamposFaltandoPreencher++;
        }

        if(document.getElementById("texto-informacao-profissional").innerHTML=="Selecione"){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementById("campo-informacao-profissional");
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementById("texto-informacao-tipo-solicitacao").innerHTML=="Selecione"){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementById("campo-informacao-tipo-solicitacao");
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementById("texto-informacao-procedimentos").innerHTML=="Selecione"){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementById("campo-informacao-procedimentos");
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementById("elemento-informacao-data").value==""){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementById("elemento-informacao-data");
            contadorCamposFaltandoPreencher++;
        }
        if(document.getElementById("elemento-informacao-hora").value==""){
            camposFaltandoPreencher[contadorCamposFaltandoPreencher]=document.getElementById("elemento-informacao-hora");
            contadorCamposFaltandoPreencher++;
        }

        for(var contadorFor=0; contadorFor<contadorCamposFaltandoPreencher; contadorFor++){
            camposFaltandoPreencher[contadorFor].style="animation: campo-nao-preenchido 5s linear 0s 1;";
        }

        if(contadorCamposFaltandoPreencher>0){
            controleAnimacao=true;
            document.getElementById("campo-mensagem").style="animation: campo-mensagem-erro 5s linear 0s 1;";
            document.getElementById("texto-mensagem").innerHTML="Por favor, complete todos os campos"
            setTimeout(function(){

                for(var contadorFor=0; contadorFor<contadorCamposFaltandoPreencher; contadorFor++){
                    camposFaltandoPreencher[contadorFor].style="";
                }

                document.getElementById("campo-mensagem").style="";
                document.getElementById("texto-mensagem").innerHTML="Os campos com * devem ser preechidos obrigatóriamente.";
                controleAnimacao=false;
                return false;
            }, 5000);
        }
        else{
            return true;
        }
    }
}

document.getElementById("botao-salvar").addEventListener("click", ()=>{
    verificarElementosAberto();
    if(verificarCamposPreenchidos()){
        var ajax=new XMLHttpRequest();
        var formulario=new FormData(document.getElementById("formulario-solicitacao"));
        ajax.onreadystatechange=function(){
            if(this.readyState==4){
                if(this.status==200){
                    console.log(this.responseText);
                }
            }
        }
        ajax.open("POST", "teste.php");
        ajax.send(formulario);
    }
});


