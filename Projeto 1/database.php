<?php

if(!isset($autorizacao_execucao_script_database)){
    header("Location: ./");
}

$autorizacao_execucao_script_credenciais_db=true;
include_once("../../../credenciais.php");

function consultarEspecificaPacientes(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $conexaoTratada=$conexao->prepare("SELECT nome, DATE_FORMAT(dataNasc, '%d/%m/%Y') AS dataFormatada, CPF FROM pacientes WHERE nome LIKE ? AND status='ativo'");
    $conteudoEnviadoConsulta="%".$_POST["consulta"]."%";
    $conexaoTratada->bind_param("s", $conteudoEnviadoConsulta);
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($nome, $dataNascimento, $cpf);

    $consulta=[];
    $contadorConsulta=0;
    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
            $consulta[$contadorConsulta]=[
                "nome"=>$nome,
                "dataNasc"=>$dataNascimento,
                "cpf"=>$cpf
            ];
            $contadorConsulta++;
        }
    }
    $conexaoTratada->close();

    $conexaoTratada=$conexao->prepare("SELECT nome, DATE_FORMAT(dataNasc, '%d/%m/%Y') AS dataFormatada, CPF FROM pacientes WHERE cpf LIKE ? AND status='ativo'");
    $conteudoEnviadoConsulta="%".$_POST["consulta"]."%";
    $conexaoTratada->bind_param("s", $conteudoEnviadoConsulta);
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($nome, $dataNascimento, $cpf);

    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){
            $consulta[$contadorConsulta]=[
                "nome"=>$nome,
                "dataNasc"=>$dataNascimento,
                "cpf"=>$cpf
            ];
            $contadorConsulta++;
        }
    }
    $conexaoTratada->close();

    return $consulta;
}

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

function consultarListagemSolicitacao(){
    global $credenciais;
    $conexao=new mysqli($credenciais["nome_servidor"], $credenciais["username"], $credenciais["senha"], $credenciais["nome_db"]);
    if($conexao->connect_error){
        die("Um erro inesperado aconteceu");
    }

    $conexaoTratada=$conexao->prepare("SELECT paciente_id, tipoSolicitacao_id, procedimentos_id, DATE_FORMAT (data, '%d-%m-%Y') AS dataFormatada, hora FROM listaSolicitacao WHERE status='ativo'");
    $conexaoTratada->execute();
    $conexaoTratada->store_result();
    $conexaoTratada->bind_result($paciente_id, $tipoSolicitacao_id, $procedimentos_id, $data, $hora);

    $conteudoListaSolicitacao=[];
    $contadorId=0;
    if($conexaoTratada->num_rows){
        while($conexaoTratada->fetch()){

            $data=explode("-", $data);
            $data=$data[0]."/".$data[1]."/".$data[2];

            $conteudoListaSolicitacao[$contadorId]=[
                "paciente_id"=>$paciente_id,
                "tipoSolicitacao_id"=>$tipoSolicitacao_id,
                "procedimentos_id"=>$procedimentos_id,
                "data"=>$data,
                "hora"=>$hora
            ];
            $contadorId++;
        }
    }
    $conexaoTratada->close();

    $pacientes=[];
    for($contadorFor=0; $contadorFor<count($conteudoListaSolicitacao); $contadorFor++){
        $conexaoTratada=$conexao->prepare("SELECT nome, CPF FROM pacientes WHERE id=? AND status='ativo'");
        $conexaoTratada->bind_param("i", $conteudoListaSolicitacao[$contadorFor]["paciente_id"]);
        $conexaoTratada->execute();
        $conexaoTratada->store_result();
        $conexaoTratada->bind_result($nomePaciente, $cpf);

        if($conexaoTratada->num_rows){
            while($conexaoTratada->fetch()){

                $pacientes[$contadorFor]=[
                    "nomePaciente"=>$nomePaciente,
                    "cpf"=>$cpf
                ];
            }
        }
        $conexaoTratada->close();
    }

    $tipoSolicitacao=[];
    for($contadorFor=0; $contadorFor<count($conteudoListaSolicitacao); $contadorFor++){

        $conexaoTratada=$conexao->prepare("SELECT descricao FROM tipoSolicitacao WHERE id=? AND status='ativo'");
        $conexaoTratada->bind_param("i", $conteudoListaSolicitacao[$contadorFor]["tipoSolicitacao_id"]);
        $conexaoTratada->execute();
        $conexaoTratada->store_result();
        $conexaoTratada->bind_result($descricao);

        if($conexaoTratada->num_rows){
            while($conexaoTratada->fetch()){
                $tipoSolicitacao[$contadorFor]=$descricao;
            }
        }
        $conexaoTratada->close();
    }

    $tipoProcedimentos=[];
    $elementosSeparados=[];
    for($contadorFor=0; $contadorFor<count($conteudoListaSolicitacao); $contadorFor++){
        $elementosSeparados[$contadorFor]=explode(";", $conteudoListaSolicitacao[$contadorFor]["procedimentos_id"]);

        $textoProcedimentos="";

        for($contadorFor2=0; $contadorFor2<count($elementosSeparados[$contadorFor]); $contadorFor2++){

            $conexaoTratada=$conexao->prepare("SELECT descricao FROM procedimentos WHERE id=? AND status='ativo'");
            $conexaoTratada->bind_param("i", $elementosSeparados[$contadorFor][$contadorFor2]);
            $conexaoTratada->execute();
            $conexaoTratada->store_result();
            $conexaoTratada->bind_result($descricao);

            if($conexaoTratada->num_rows){
                while($conexaoTratada->fetch()){
                    $textoProcedimentos.=$descricao.", ";
                }
            }
            $conexaoTratada->close();
        }

        $tipoProcedimentos[$contadorFor]=substr($textoProcedimentos, 0, strlen($textoProcedimentos)-2);
    }

    $retorno=[];
    for($contadorFor=0; $contadorFor<count($conteudoListaSolicitacao); $contadorFor++){
        $retorno[$contadorFor]=[
            "nomePaciente"=>$pacientes[$contadorFor]["nomePaciente"],
            "cpf"=>$pacientes[$contadorFor]["cpf"],
            "tipoSolicitacao"=>$tipoSolicitacao[$contadorFor],
            "tipoProcedimentos"=>$tipoProcedimentos[$contadorFor],
            "data"=>$conteudoListaSolicitacao[$contadorFor]["data"],
            "hora"=>$conteudoListaSolicitacao[$contadorFor]["hora"]
        ];
    }

    return $retorno;
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
    $conexaoTratada=$conexao->prepare("INSERT INTO listaSolicitacao (paciente_id, tipoSolicitacao_id, procedimentos_id, data, hora, status) VALUES (?, ?, ?, ?, ?, ?)");
    $conexaoTratada->bind_param("iissss", $paciete_id, $tipoSolicitacao_id, $procedimentos_id, $_POST["dataEscolhida"], $_POST["horaEscolhida"], $status);
    $conexaoTratada->execute();
    $conexaoTratada->close();

    return $conexao->insert_id;
}

?>