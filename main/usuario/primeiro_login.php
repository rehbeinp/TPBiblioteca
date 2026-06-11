<?php
require_once "funcoes.php";

session_start();
$mensagem = "A nova senha deve ter no mínimo 8 caracteres.";
if(isset($_SESSION['status']) and $_SESSION['status']!= "" ){
    $user = $_SESSION['status'];
}
else{
    header("location: ../login.php");
}

if(isset($_POST['btn_atualizar'])){
    $senhaAtual = $_POST['senhaAtual'];
    $senhaNova = $_POST['senhaNova'];
    $senhaNovaConfirma = $_POST['senhaNovaConfirma'];

    if(strlen(trim($senhaNova)) < 8){
        $mensagem = "Informe uma senha com mais de oito caracteres.";
    }
    elseif($senhaNova != $senhaNovaConfirma){
            $mensagem = "Os dois campos de nova senha e de confirma senha devem ter a mesma senha.";
    }
    else{
        $senhaAtual = hash('sha256', (string)$senhaAtual);
        $senhaNova = hash('sha256', (string)$senhaNova);
        $sql = "UPDATE usuarios set senha = '$senhaNova' where email = '$user' and senha = '$senhaAtual'";
        
        if(atualizar_dado($sql)){
            $mensagem = "Dados atualizados com sucesso!";
        }
        else {
            $mensagem = "A atualização falhou, tente novamente!";
        }
    }
}

if(isset($_POST['btn_login'])){
    header("location: ../login.php");
}
?>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <form  method='POST'>
        <p> 
            <button type='submit' name='btn_login'> Voltar para login </button>
        </p>
    </form>
    <h2>Primeiro Login</h2> 
    <p>Como é seu primeiro login, sua senha deve ser redefinida para a segurança da sua conta.</p>
    <form  method='POST'>
        <div>
            <p> <strong> Usuario <?php echo $user;?>.</strong> </p><br>
            <h3> Alterar senha </h3>
            <strong> Senha atual </strong>
            <input type='password' name='senhaAtual' required> <br><br>
            <strong> Nova senha </strong>
            <input type='password' name='senhaNova' required>
            <strong> Confirma nova senha </strong>
            <input type='password' name='senhaNovaConfirma' required><br>
            <?php echo $mensagem;?>
            <p> 
                <button type='submit' name='btn_atualizar'> Atualizar senha </button>
            </p>
        </div>
	</form>

</body>
</html>