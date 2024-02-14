<?php

$autorizacao_execucao_script_database=true;
require_once("database.php");
$retornoListagemSolicitacalDB=consultarListagemSolicitacao();

echo '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Pacientes</title>
    <link rel="stylesheet" href="lista.css">
    <script defer src="lista.js"></script>
</head>
<body>
    
    <header id="campo-cabecalho">
        <div id="campo-botao-cabecalho">
            <button class="botao-cabecalho" onclick="window.location.href=\'solicitacao.php\'">Solicitações Clínicas</button>
            <button class="botao-cabecalho" onclick="window.location.href=\'lista.php\'">Listagem de Solicitações</button>
        </div>
    </header>

    <article id="campo-corpo">

        <section id="sessao-consulta">
            <table id="tabela-consulta">
                <tr class="linha-tabela-cabecalho">
                    <th class="coluna-cabecalho-tabela">Nome Paciente</th>
                    <th class="coluna-cabecalho-tabela">CPF</th>
                    <th class="coluna-cabecalho-tabela">Tipo de Solicitação</th>
                    <th class="coluna-cabecalho-tabela">Procedimento(s)</th>
                    <th class="coluna-cabecalho-tabela">Data</th>
                    <th class="coluna-cabecalho-tabela">Hora</th>
                </tr>
';

// Adicionando pacientes da tabela

$quantidadeRetornada=count($retornoListagemSolicitacalDB);
for($contadorFor=0; $contadorFor<$quantidadeRetornada; $contadorFor++){

    echo'
                <tr class="linha-tabela-conteudo">
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["nomePaciente"].'</td>
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["cpf"].'</td>
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["tipoSolicitacao"].'</td>
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["tipoProcedimentos"].'</td>
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["data"].'</td>
                    <td class="coluna-conteudo-tabela">'.$retornoListagemSolicitacalDB[$contadorFor]["hora"].'</td>
                </tr>';
}

echo'
            </table>
        </section>
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