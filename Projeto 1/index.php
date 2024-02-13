<?php

if(!isset($_GET["p"])){
    header("Location:?p=1");
}

$autorizacao_execucao_script_database=true;
require_once("database.php");
$retornoConsultaDB=consultarPacientes();

echo '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Pacientes</title>
    <link rel="stylesheet" href="index.css">
    <script defer src="index.js"></script>
</head>
<body>
    
    <header id="campo-cabecalho">
        <div id="campo-botao-cabecalho">
            <button class="botao-cabecalho">Solicitações Clínicas</button>
            <button class="botao-cabecalho">Listagem de Solicitações</button>
        </div>
    </header>

    <article id="campo-corpo">
        <div id="elemento-pesquisa">
            <img src="icones/search.png">
            <input id="input-pesquisa" type="text" placeholder="Pesquisar">
        </div>

        <section id="sessao-consulta">
            <table id="tabela-consulta">
                <tr class="linha-tabela-cabecalho">
                    <th class="coluna-cabecalho-tabela">Paciente</th>
                    <th class="coluna-cabecalho-tabela">Nascimento</th>
                    <th class="coluna-cabecalho-tabela">CPF</th>
                    <th class="coluna-cabecalho-tabela">Ações</th>
                </tr>
';

// Adicionando pacientes da tabela

$quantidadePacientes=count($retornoConsultaDB["pacientes"]);
for($contadorPacientes=0; $contadorPacientes<$quantidadePacientes; $contadorPacientes++){
    $nomePacienteAtual=$retornoConsultaDB["pacientes"][$contadorPacientes]["nome"];
    $dataNascPacienteAtual=$retornoConsultaDB["pacientes"][$contadorPacientes]["dataNasc"];
    $cpfPacienteAtual=$retornoConsultaDB["pacientes"][$contadorPacientes]["cpf"];

    echo'
                <tr class="linha-tabela-conteudo">
                    <td class="coluna-conteudo-tabela">'.$nomePacienteAtual.'</td>
                    <td class="coluna-conteudo-tabela">'.$dataNascPacienteAtual.'</td>
                    <td class="coluna-conteudo-tabela">'.$cpfPacienteAtual.'</td>
                    <td class="coluna-conteudo-tabela-botao"><button class="botao-prosseguir-tabela" onclick="enviarDadosParaSolicitacao(this)">Prosseguir</button></td>
                </tr>';
}

echo'
            </table>
        </section>
        <section id="sessao-paginador">
            <div id="campo-paginador">
                <div id="elementos-paginador">';

$quantidadePagina=$retornoConsultaDB["quantidadePaginasPacientes"];
settype($quantidadePagina, "integer");
$paginaAtual=$retornoConsultaDB["paginaAtual"];

if($paginaAtual==1){
    echo'
                        <img id="seta-anterior-pagina" style="opacity: 0.3" src="icones/seta.png">';
}
else{
    echo'               <a href="./?p='.($paginaAtual-1).'">
                            <img id="seta-anterior-pagina" src="icones/seta.png">
                        </a>';
}

if($quantidadePagina>5){
    if($paginaAtual==1){
        for($contadorPagina=0; $contadorPagina<3; $contadorPagina++){
            if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
                echo '<a id="elemento-pagina-atual">'.($contadorPagina+1).'</a>';
            }
            else{
                echo '<a class="elemento-pagina" href="./?p='.($contadorPagina+1).'">'.($contadorPagina+1).'</a>';
            }
        }
        echo '<a class="elemento-pagina">...</a>';
        echo '<a class="elemento-pagina"  href="./?p='.($quantidadePagina).'">'.($quantidadePagina).'</a>';
    }
    else if(($paginaAtual+3)<$quantidadePagina){
        for($contadorPagina=$paginaAtual-2; $contadorPagina<($paginaAtual+1); $contadorPagina++){
            if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
                echo '<a id="elemento-pagina-atual">'.($contadorPagina+1).'</a>';
            }
            else{
                echo '<a class="elemento-pagina" href="./?p='.($contadorPagina+1).'">'.($contadorPagina+1).'</a>';
            }
        }
        echo '<a class="elemento-pagina">...</a>';
        echo '<a class="elemento-pagina"  href="./?p='.($quantidadePagina).'">'.($quantidadePagina).'</a>';
    }
    else{
        for($contadorPagina=($quantidadePagina-4); $contadorPagina<$quantidadePagina+1; $contadorPagina++){
            if(($contadorPagina)==$retornoConsultaDB["paginaAtual"]){
                echo '<a id="elemento-pagina-atual">'.($contadorPagina).'</a>';
            }
            else{
                echo '<a class="elemento-pagina" href="./?p='.($contadorPagina).'">'.($contadorPagina).'</a>';
            }
        }
    }
}

else{
    for($contadorPagina=0; $contadorPagina<$quantidadePagina; $contadorPagina++){
        if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
            echo '<a id="elemento-pagina-atual">'.($contadorPagina+1).'</a>';
        }
        else{
            echo '<a class="elemento-pagina" href="./?p='.($contadorPagina+1).'">'.($contadorPagina+1).'</a>';
        }
    }
}

if($paginaAtual==$quantidadePagina){
    echo'
                        <img id="seta-proxima-pagina" style="opacity: 0.3" src="icones/seta.png">';
}
else{
    echo'               <a href="./?p='.($paginaAtual+1).'">
                            <img id="seta-proxima-pagina" src="icones/seta.png">
                        </a>';
}

echo'
                </div>
            </div>
        </section>
    </article>
    <footer id="campo-rodape">
        <p id="texto-rodape">Rhafaell_SITCON</p>
    </footer>

    <form id="prosseguir" method="POST" action="solicitacao.php">
        <input id="elemento-prosseguir-nome" type="hidden" name="nome">
        <input id="elemento-prosseguir-dataNasc" type="hidden" name="dataNasc">
        <input id="elemento-prosseguir-cpf" type="hidden" name="cpf">
    </form>

</body>
</html>';

?>