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
            <input id="input-pesquisa" placeholder="Pesquisar" type="text">
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
                    <td class="coluna-conteudo-tabela-botao"><button class="botao-prosseguir-tabela">Proseguir</button></td>
                </tr>';
}

echo'
            </table>
        </section>
        <section id="sessao-paginador">
            <div id="campo-paginador">
                <ul id="elementos-paginador">
                    <img id="seta-anterior-pagina" src="icones/seta.png">';

$quantidadePagina=$retornoConsultaDB["quantidadePaginasPacientes"];
settype($quantidadePagina, "integer");
$paginaAtual=$retornoConsultaDB["paginaAtual"];

if($quantidadePagina>5){
    if($paginaAtual==1){
        for($contadorPagina=0; $contadorPagina<3; $contadorPagina++){
            if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
                echo '<li id="elemento-pagina-atual">'.($contadorPagina+1).'</li>';
            }
            else{
                echo '<li class="elemento-pagina">'.($contadorPagina+1).'</li>';
            }
        }
        echo '<li class="elemento-pagina">...</li>';
        echo '<li class="elemento-pagina">'.$quantidadePagina.'</li>';
    }
    else if(($paginaAtual+4)<$quantidadePagina){
        for($contadorPagina=$paginaAtual-2; $contadorPagina<($paginaAtual+1); $contadorPagina++){
            if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
                echo '<li id="elemento-pagina-atual">'.($contadorPagina+1).'</li>';
            }
            else{
                echo '<li class="elemento-pagina">'.($contadorPagina+1).'</li>';
            }
        }
        echo '<li class="elemento-pagina">...</li>';
        echo '<li class="elemento-pagina">'.$quantidadePagina.'</li>';
    }
    else{
        for($contadorPagina=($quantidadePagina-4); $contadorPagina<$quantidadePagina+1; $contadorPagina++){
            if(($contadorPagina)==$retornoConsultaDB["paginaAtual"]){
                echo '<li id="elemento-pagina-atual">'.($contadorPagina).'</li>';
            }
            else{
                echo '<li class="elemento-pagina">'.($contadorPagina).'</li>';
            }
        }
    }
}

else{
    for($contadorPagina=0; $contadorPagina<5; $contadorPagina++){
        if(($contadorPagina+1)==$retornoConsultaDB["paginaAtual"]){
            echo '<li id="elemento-pagina-atual">'.($contadorPagina+1).'</li>';
        }
        else{
            echo '<li class="elemento-pagina">'.($contadorPagina+1).'</li>';
        }
    }
}

echo'
                    <img id="seta-proxima-pagina" src="icones/seta.png">
                </ul>
            </div>
        </section>
    </article>
    <footer id="campo-rodape">
        <p id="texto-rodape">Rhafaell_SITCON</p>
    </footer>

</body>
</html>';

?>