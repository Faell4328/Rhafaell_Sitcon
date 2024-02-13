<?php

$autorizacao_execucao_script_database=true;
require_once("database.php");

if(isset($_POST["nomePaciente"]) && isset($_POST["dataNascPaciente"]) && isset($_POST["cpfPaciente"]) && isset($_POST["nomeProfissional"]) && isset($_POST["nomeSolicitacao"]) && isset($_POST["dataEscolhida"]) && isset($_POST["horaEscolhida"])){
    if(cadastrarDBListaSolicitacao()){
        die("Salvo");
    }
    else{
        die("Não");
    }
}

$retornoConsultaDBProfissionais=consultarDBProfissionais();
$retornoConsultaDBTipoSolicitacao=consultarDBTipoSolicitacao();
$retornoConsultaDBProcedimentos=consultarDBProcedimentos();

echo '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitação Clínicas</title>
    <link rel="stylesheet" href="solicitacao.css">
    <script defer src="solicitacao.js"></script>
</head>
<body>

    <header id="campo-cabecalho">
        <div id="campo-botao-cabecalho">
            <button class="botao-cabecalho">Solicitações Clínicas</button>
            <button class="botao-cabecalho">Listagem de Solicitações</button>
        </div>
    </header>

    <article id="campo-corpo">
        <button id="botao-voltar" onclick="window.history.back()">Voltar</button>

        <form id="formulario-solicitacao">
            <section class="sessao-solicitacao">';

if(isset($_POST["nome"]) && isset($_POST["dataNasc"]) && isset($_POST["cpf"])){
    echo'
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">Nome do Paciente</label>
                    <input class="elemento-informacao-paciente" type="text" name="nomePaciente" value="'.$_POST["nome"].'">
                </div>
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">Data de Nascimento</label>
                    <input class="elemento-informacao-paciente" type="text" name="dataNascPaciente" maxlength="10" value="'.$_POST["dataNasc"].'">
                </div>
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">CPF</label>
                    <input class="elemento-informacao-paciente" type="text" name="cpfPaciente" maxlength="14" value="'.$_POST["cpf"].'">
                </div>';
}
else{
    echo'
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">Nome do Paciente</label>
                    <input class="elemento-informacao-paciente" type="text" name="nomePaciente">
                </div>
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">Data de Nascimento</label>
                    <input class="elemento-informacao-paciente" type="text" name="dataNascPaciente" maxlength="10">
                </div>
                <div class="campo-solicitacao-paciente">
                    <label class="descricao-elemento">CPF</label>
                    <input class="elemento-informacao-paciente" type="text" name="cpfPaciente" maxlength="14">
                </div>';
}
echo'
            </section>
            <section class="sessao-solicitacao">
                <div id="campo-mensagem">
                    <b>Atenção! </b> <span id="texto-mensagem">Os campos com * devem ser preechidos obrigatóriamente.<span>
                </div>
            </section>
            <section class="sessao-solicitacao">
                <div class="campo-solicitacao-dados">
                    <label class="descricao-elemento">Profissional*</label>
                    <div id="campo-informacao-profissional">
                        <p id="texto-informacao-profissional">Selecione</p>
                        <img class="seta-informacao" src="icones/seta2.png">
                    </div>
                    <div id="campo-opcoes-informacao-profissional">';

// Adicionando a lista os profissionais
for($contadorFor=0; $contadorFor<count($retornoConsultaDBProfissionais); $contadorFor++){
    echo '
                        <div class="campo-grupo-opcoes-informacao-profissional">
                            <input class="elementos-opcoes-profissional" type="radio" name="nomeProfissional" value="'.$retornoConsultaDBProfissionais[$contadorFor].'">
                            <label class="texto-informacao-opcoes">'.$retornoConsultaDBProfissionais[$contadorFor].'</label>
                        </div>';
}

echo'
                    </div>
                </div>
            </section>

            <section class="sessao-solicitacao">
                <div class="campo-solicitacao-dados">
                    <label class="descricao-elemento">Tipo de Solicitação*</label>
                    <div id="campo-informacao-tipo-solicitacao">
                        <p id="texto-informacao-tipo-solicitacao">Selecione</p>
                        <img class="seta-informacao" src="icones/seta2.png">
                    </div>
                    <div id="campo-opcoes-informacao-tipo-solicitacao">';

// Adicionando a lista os tipos de solicitação
for($contadorFor=0; $contadorFor<count($retornoConsultaDBTipoSolicitacao); $contadorFor++){
    echo '
                        <div class="campo-grupo-opcoes-informacao-tipo-solicitacao">
                            <input class="elementos-opcoes-tipo-solicitacao" type="radio" name="nomeSolicitacao" value="'.$retornoConsultaDBTipoSolicitacao[$contadorFor].'">
                            <label class="texto-informacao-opcoes">'.$retornoConsultaDBTipoSolicitacao[$contadorFor].'</label>
                        </div>';
}

echo'
                    </div>
                </div>

                <div class="campo-solicitacao-dados">
                    <label class="descricao-elemento">Procedimentos*</label>
                    <div id="campo-informacao-procedimentos">
                        <p id="texto-informacao-procedimentos">Selecione</p>
                        <img class="seta-informacao" src="icones/seta2.png">
                    </div>
                    <div class="campo-opcoes-informacao-procedimentos">';

// Adicionando a lista os procedimentos do tipo consulta
for($contadorFor=0; $contadorFor<count($retornoConsultaDBProcedimentos["consulta"]); $contadorFor++){
    echo '
                        <div class="campo-grupo-opcoes-informacao-procedimentos">
                            <input class="elementos-opcoes-informacao-procedimentos" type="radio" name="nomeProcedimentosConsulta" value="'.$retornoConsultaDBProcedimentos["consulta"][$contadorFor].'">
                            <label class="texto-informacao-opcoes">'.$retornoConsultaDBProcedimentos["consulta"][$contadorFor].'</label>
                        </div>';
}

echo'
                    </div>
                    <div class="campo-opcoes-informacao-procedimentos">';

// Adicionando a lista os procedimentos do tipo exame
for($contadorFor=0; $contadorFor<count($retornoConsultaDBProcedimentos["exame"]); $contadorFor++){
    echo '
                        <div class="campo-grupo-opcoes-informacao-procedimentos">
                            <input class="elementos-opcoes-informacao-procedimentos" type="checkbox" name="nomeProcedimentosExame'.$contadorFor.'" value="'.$retornoConsultaDBProcedimentos["exame"][$contadorFor].'">
                            <label class="texto-informacao-opcoes">'.$retornoConsultaDBProcedimentos["exame"][$contadorFor].'</label>
                        </div>';
}

echo'
                    </div>
                </div>
            </section>

            <section class="sessao-solicitacao">
                <div class="campo-solicitacao-dados">
                    <label class="descricao-elemento">Data*</label>
                    <input id="elemento-informacao-data" type="date" name="dataEscolhida">
                </div>

                <div class="campo-solicitacao-dados">
                    <label class="descricao-elemento">Hora*</label>
                    <input id="elemento-informacao-hora" type="time" name="horaEscolhida">
                </div>
            </section>

            <section class="sessao-solicitacao">
                <input id="botao-salvar" type="button" value="Solicitações Clínicas">
            </section>
        </form>
    </article>

</body>
</html>';

?>



