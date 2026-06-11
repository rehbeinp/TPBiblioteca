<?php
include "head_usuario.php";
require_once "funcoes.php";

$btn_nome = "Alterar nome";
$btn_nascimento = "Alterar data";
$btn_endereco = "Alterar endereço";
$btn_email = "Alterar email";
$btn_senha= "Alterar senha";
$btn_cancelar = "";

$campo_nome = "";
$campo_nascimento = "";
$campo_endereco = "";
$campo_email = "";
$campo_senha= "";
$mensagem = "";
$mensagem_retorno = "";
session_start();
$num_linhas = 0;
$resul = '';

function busca_dados($u){
    $user = $u;

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql_user = "SELECT 
                nome,
                cpf,
                data_nascimento,
                endereco,
                email
            from usuarios
            where email='$user'";


    $exesql_user = mysqli_query($con, $sql_user);

    mysqli_close($con);
    $resul = mysqli_fetch_assoc($exesql_user);
    return $resul;

}


if(isset($_SESSION['status']) and $_SESSION['status']!= "" ){
    $user = $_SESSION['status'];
    $resul = busca_dados($user);
}
else{
    header("location: ../login.php");
}

if(isset($_POST['btn_nome'])){
    $mensagem_retorno = "";
    if($btn_nome == "Alterar nome"){
    $campo_nome = "<br> <strong>Novo nome: </strong><input type='text' name='nome'><br>";
    $btn_nome = "Salvar nome";
    $btn_cancelar = "<button type='submit' name='btn_cancelar'> Cancelar Alteração</button>";
    }
    if($btn_nome == "Salvar nome"){
        $nome = $_POST['nome'];
        if(trim($nome) == "" or strlen(trim($nome)) < 3){
            $mensagem = "Informe um nome com mais de duas letras.";
        }
        else{
            $sql = "UPDATE usuarios set nome = '$nome' where email = '$user'";
            if(atualizar_dado($sql)){
                
                $btn_nome = "Alterar nome";
                $campo_nome = "";
                $mensagem_retorno = "Dados atualizados com sucesso!";

            }
            else {
                $mensagem_retorno = "A atualização falhou, tente novamente!";
            }
            ;

        }
    }
}

if(isset($_POST['btn_nascimento'])){
    $mensagem_retorno = "";
    if($btn_nascimento == "Alterar data"){
    $campo_nascimento = "<br> <strong>Nova data: </strong><input type='date' name='nascimento'><br>";
    $btn_nascimento = "Salvar data";
    $btn_cancelar = "<button type='submit' name='btn_cancelar'> Cancelar Alteração</button>";
    }

    if($btn_nascimento == "Salvar data"){
        $data = $_POST['nascimento'];
        $nascimento = new DateTime($_POST['nascimento']);
        $hoje = new DateTime('now');
        $intervalo = $hoje->diff( $nascimento );
        if($intervalo->y < 3){
           $mensagem = "Informe uma data válida (você deve ter no minímo 3 anos).";
        }
        else{
            $sql = "UPDATE usuarios set data_nascimento = '$data' where email = '$user'";
            if(atualizar_dado($sql)){
                
                $btn_nascimento = "Alterar data";
                $campo_nascimento = "";
                $mensagem_retorno = "Dados atualizados com sucesso!";

            }
            else {
                $mensagem_retorno = "A atualização falhou, tente novamente!";
            }
            
        }
    }
}

if(isset($_POST['btn_endereco'])){
    $mensagem_retorno = "";
    if($btn_endereco == "Alterar endereço"){
    $campo_endereco = " <br> <strong>Cidade: </strong><input type='text' name='cidade'>
                        <br> <strong>Bairro: </strong><input type='text' name='bairro'>
                        <br> <strong>Rua: </strong><input type='text' name='rua'>
                        <br> <strong>Número: </strong><input type='text' name='numero'>
                        <br> <strong>CEP: </strong><input type='text' name='cep'><br>
                       ";
    $btn_endereco = "Salvar endereço";
    $btn_cancelar = "<button type='submit' name='btn_cancelar'> Cancelar Alteração</button>";
    }
    if($btn_endereco == "Salvar endereço"){
        $cidade = trim($_POST['cidade']);
        $bairro = trim($_POST['bairro']);
        $rua = trim($_POST['rua']);
        $numero = trim($_POST['numero']);
        $cep = trim($_POST['cep']);

        if(strlen($cidade) < 5 or strlen($bairro) < 5 or strlen($rua) < 5 or
            strlen($numero) == "" or strlen($cep) < 8 ){
            $mensagem = "Preencha todos os campos";
        }
        else{
            $endereco = "$cidade, $bairro, $rua, n°$numero - $cep";
            $sql = "UPDATE usuarios set endereco = '$endereco' where email = '$user'";
            if(atualizar_dado($sql)){
                
                $btn_endereco = "Alterar endereço";
                $campo_endereco = "";
                $mensagem = "Dados atualizados com sucesso!";

            }
            else {
                $mensagem = "A atualização falhou, tente novamente!";
            }
            ;

        }

    }
}

if(isset($_POST['btn_email'])){
    $mensagem_retorno = "";
    if($btn_email == "Alterar email"){
    $campo_email = "<br> <strong>Novo email: </strong><input type='email' name='email'><br>";
    $btn_email = "Salvar email";
    $btn_cancelar = "<button type='submit' name='btn_cancelar'> Cancelar Alteração</button>";
    }
    if($btn_email == "Salvar email"){
        $email = $_POST['email'];
        if(trim($email) == "" or strlen(trim($email)) < 5){
            $mensagem = "Informe um email com mais de duas letras.";
        }
        else{
            $sql = "UPDATE usuarios set email = '$email' where email = '$user'";
            if(atualizar_dado($sql)){
                
                $btn_email = "Alterar email";
                $campo_email = "";
                $_SESSION['status'] = $email;
                $mensagem_retorno = "Dados atualizados com sucesso!";

            }
            else {
                $mensagem_retorno = "A atualização falhou, tente novamente!";
            }
            ;

        }
    }
}

if(isset($_POST['btn_senha'])){
    $mensagem_retorno = "";
    if($btn_senha == "Alterar senha"){
    $campo_senha = "<br><strong>Nova senha: </strong><input type='password' name='senha'>
    <br> <strong>Confirmar senha: </strong><input type='password' name='confirma_senha'><br>";
    $btn_senha = "Salvar senha";
    $btn_cancelar = "<button type='submit' name='btn_cancelar'> Cancelar Alteração</button>";
    }
    if($btn_senha == "Salvar senha"){
        
        $senha = $_POST['senha'];
        $confirma_senha = $_POST['confirma_senha'];

        if(strlen(trim($senha)) < 8){
            $mensagem = "Informe uma senha com mais de oito caracteres.";
        }
        elseif($senha != $confirma_senha){
                $mensagem = "Os dois campos devem ter a mesma senha";
        }
        else{
            $senha = hash('sha256', (string)$senha);
            $sql = "UPDATE usuarios set senha = '$senha' where email = '$user'";
            if(atualizar_dado($sql)){
                
                $btn_senha = "Alterar senha";
                $campo_senha = "";
                $mensagem_retorno = "Dados atualizados com sucesso!";

            }
            else {
                $mensagem_retorno = "A atualização falhou, tente novamente!";
            }
            ;

        }
    
    }
}

if(isset($_POST['btn_cancelar'])){
    $mensagem = "";
    header("location: alterar_dados.php");
}

if(isset($_POST['btn_ver_dados'])){
    header("location: conta_usuario.php");
}
?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <h2>Informações sobre a sua conta</h2> 
    <form  method='POST'>
        <div>
            <p><?php 
            echo "<h3>Alterar dados cadastrados:</h3>";

            echo "<h5>$mensagem_retorno</h5>";
    
            echo "<strong>Nome: </strong>" . $resul['nome'];
            echo " $campo_nome";
            echo "<button type='submit' name='btn_nome'> $btn_nome </button>";
            if($btn_nome == "Salvar nome"){
                echo "    $btn_cancelar <br>";
                echo "$mensagem";
            }
            

            echo "<br> <br><strong>Data Nascimento: </strong>" . $resul['data_nascimento'];
            echo " $campo_nascimento";
            echo "<button type='submit' name='btn_nascimento'> $btn_nascimento </button>";
            if($btn_nascimento=="Salvar data"){
                echo "    $btn_cancelar <br>";
                echo "$mensagem";
            }

            echo "<br> <br><strong>Endereço: </strong>" . $resul['endereco'];
            echo " $campo_endereco";
            echo "<button type='submit' name='btn_endereco'> $btn_endereco </button>";
            if($btn_endereco=="Salvar endereço"){
                echo "    $btn_cancelar <br>";
                echo "$mensagem";
            }

            echo "<br> <br><strong>Email: </strong>" . $resul['email'];
            echo " $campo_email";
            echo "<button type='submit' name='btn_email'> $btn_email </button>";
            if($btn_email=="Salvar email"){
                echo "    $btn_cancelar <br>";
                echo "$mensagem";
            }

            echo "<br> <br><strong>CPF/CNPJ: </strong>" . $resul['cpf'];

            echo "<br> <br><strong> Senha </strong>";
            echo "$campo_senha";
            echo "<button type='submit' name='btn_senha'> $btn_senha </button>";
            if($btn_senha=="Salvar senha"){
                echo "    $btn_cancelar <br>";
                echo "$mensagem";
            }
            echo "<br>";
            ?>
            </p>
            <p> 
                <button type='submit' name='btn_ver_dados'> Voltar para minha conta </button>
            </p>
        </div>
	</form>

</body>
</html>