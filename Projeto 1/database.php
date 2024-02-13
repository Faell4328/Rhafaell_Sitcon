<?php

if(!isset($autorizacao_execucao_script_database)){
    header("Location: ./");
}

$autorizacao_execucao_script_credenciais_db=true;
include_once("../../../credenciais.php");

function consultarQuantidadeTotalLinhas(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }
    
    $sql="SELECT COUNT(*) AS id FROM pacientes WHERE status='ativo'";
    $resultadoConsulta=$conexao->query($sql);
    if(!$resultadoConsulta){
        die("Nada foi retornado");
    }
    $quantidadeTotalLinhas=$resultadoConsulta->fetch_assoc();

    return $quantidadeTotalLinhas["id"];
}

function consultarPacientes(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $paginaAtual=preg_replace("/[^0-9]/", "", $_GET["p"]);
    settype($paginaAtual, "integer");
    if($paginaAtual<=0){
        header("Location:?p=1");
    }

    $quantidadeTotalLinhas=consultarQuantidadeTotalLinhas();

    if($quantidadeTotalLinhas%10==0){
        $quantidadePaginasPacientes=$quantidadeTotalLinhas/10;
    }
    else{
        $quantidadePaginasPacientes=($quantidadeTotalLinhas/10);
        settype($quantidadePaginasPacientes, "int");
        $quantidadePaginasPacientes++;
    }

    if($paginaAtual>$quantidadePaginasPacientes){
        die("Essa página não existe");
    }
    
    $ponteiroPaginaAtual=($paginaAtual-1)*10;
    $conexaoTratada=$conexao->prepare("SELECT nome, DATE_FORMAT(dataNasc, '%d/%m/%Y') AS dataFormatada, CPF FROM pacientes WHERE status='ativo' LIMIT 10 OFFSET ?");
    $conexaoTratada->bind_param("i", $ponteiroPaginaAtual);
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($nome, $dataNascimento, $cpf);

    $pacientes=[];
    $contadorPacientes=0;
    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
            $pacientes[$contadorPacientes]=[
                "nome"=>$nome,
                "dataNasc"=>$dataNascimento,
                "cpf"=>$cpf
            ];
            $contadorPacientes++;
        }
    }

    $retornoFuncao=[
        "pacientes"=>$pacientes,
        "paginaAtual"=>$paginaAtual,
        "quantidadePaginasPacientes"=>$quantidadePaginasPacientes
    ];

    return $retornoFuncao;
}

function consultarDBProfissionais(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $conexaoTratada=$conexao->prepare("SELECT nome FROM profissional WHERE status='ativo'");
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($nome);

    $profissionais=[];
    $contadorProfissionais=0;
    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
            $profissionais[$contadorProfissionais]=$nome;
            $contadorProfissionais++;
        }
    }

    return $profissionais;
}

function consultarDBTipoSolicitacao(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $conexaoTratada=$conexao->prepare("SELECT descricao FROM tipoSolicitacao WHERE status='ativo'");
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($descricao);

    $tipoConsulta=[];
    $contadorTipoConsulta=0;
    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
            $tipoConsulta[$contadorTipoConsulta]=$descricao;
            $contadorTipoConsulta++;
        }
    }

    return $tipoConsulta;
}

function consultarDBProcedimentos(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $conexaoTratada=$conexao->prepare("SELECT descricao, tipo_id FROM procedimentos WHERE status='ativo'");
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($descricao, $tipo_id);

    $procedimentos=[];
    $procedimentosConsulta=[];
    $procedimentosExame=[];

    $contadorProcedimentosConsulta=0;
    $contadorProcedimentosExame=0;

    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
        
            // Verficia se é uma consulta
            if($tipo_id==1){
                $procedimentosConsulta[$contadorProcedimentosConsulta]=$descricao;
                $contadorProcedimentosConsulta++;
            }
            else{
                $procedimentosExame[$contadorProcedimentosExame]=$descricao;
                $contadorProcedimentosExame++;
            }
        }
    }

    $procedimentos["consulta"]=$procedimentosConsulta;
    $procedimentos["exame"]=$procedimentosExame;

    return $procedimentos;
}

function cadastrarDBListaSolicitacao(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $dataNasc=explode("/", $_POST["dataNascPaciente"]);
    $dataNasc=$dataNasc[2]."-".$dataNasc[1]."-".$dataNasc[0];

    $conexaoTratada=$conexao->prepare("SELECT id FROM pacientes WHERE nome=? AND CPF=?");
    $conexaoTratada->bind_param("ss", $_POST["nomePaciente"], $_POST["cpfPaciente"]);
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($paciete_id);
    $conexaoTratada->fetch();
    $conexaoTratada->close();

    $conexaoTratada=$conexao->prepare("SELECT id FROM tipoSolicitacao WHERE descricao=?");
    $conexaoTratada->bind_param("s", $_POST["nomeSolicitacao"]);
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($tipoSolicitacao_id);
    $conexaoTratada->fetch();
    $conexaoTratada->close();

    $procedimentos_id="";
    if($_POST["nomeSolicitacao"]=="Exames Laboratoriais"){
        for($contadorFor=0; $contadorFor<10; $contadorFor++){
            if(isset($_POST["nomeProcedimentosExame".(string) $contadorFor])){

                $copiaProcedimentos_id="";
                $conexaoTratada=$conexao->prepare("SELECT id FROM procedimentos WHERE descricao=?");
                $conexaoTratada->bind_param("s", $_POST["nomeProcedimentosExame".(string) $contadorFor]);
                $conexaoTratada->execute();
                $conexaoTratada->store_result();
                $conexaoTratada->bind_result($copiaProcedimentos_id);
                $conexaoTratada->fetch();
                $conexaoTratada->close();
        
                $procedimentos_id.=$copiaProcedimentos_id.";";
            }
        }
        $procedimentos_id=substr($procedimentos_id, 0, strlen($procedimentos_id)-1);
    }
    else{
        $procedimentos_id="";
        $conexaoTratada=$conexao->prepare("SELECT id FROM procedimentos WHERE descricao=?");
        $conexaoTratada->bind_param("s", $_POST["nomeProcedimentosConsulta"]);
        $conexaoTratada->execute();
        $conexaoTratada->store_result();
        $conexaoTratada->bind_result($procedimentos_id);
        $conexaoTratada->fetch();
        $conexaoTratada->close();
        
        settype($procedimentos_id, "string");
    }

    $status="ativo";
    $conexaoTratada=$conexao->prepare("INSERT INTO listaSolicitacao (paciente_id, tipoSolicitacao_id, procedimentos_id, status) VALUES (?, ?, ?, ?)");
    $conexaoTratada->bind_param("iiss", $paciete_id, $tipoSolicitacao_id, $procedimentos_id, $status);
    $conexaoTratada->execute();
    $conexaoTratada->close();

    return $conexao->insert_id;
}

?>