<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
</head>
<form method="POST">
    <h1>Login</h1>
    <label for='email'>Usuario</label>
    <input type="text" name="usuario" required>

    <label for='senha'>Senha</label>
    <input type="password" name="senha" required> <br>
    <input type="radio" name="tipoUsusario" value="admin" /> Administrador
    <input type="radio" name="tipoUsusario" value="usuario" /> Usuário Padrão <br>

    <button type='submit' name='btn_login'>Entrar</button>
</form>

</html>

<?php
session_start();
require_once "usuario/funcoes.php";

if (isset($_POST["btn_login"])) {
    $USER = $_POST["usuario"];
    $SENHA = hash('sha256', (string)$_POST["senha"]);
    $tipoUsuario = $_POST["tipoUsusario"];

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql = "SELECT email from usuarios where email='$USER' 
        and senha='$SENHA' and tipo = '$tipoUsuario'";

    $exesql = mysqli_query($con, $sql);
    mysqli_close($con);

    $num_linhas = mysqli_num_rows($exesql);

    if ($num_linhas == 1) {
        $_SESSION['status'] = $USER;

        echo "Usuario logado com sucesso";
        
        $multa_atualizada = calcula_multa($USER);
        atualiza_multa($multa_atualizada);
        

        if ($tipoUsuario == "admin") {
            header("location: menu_adim.php");
        } else {
            header("location: usuario/menu_usuario.php");
        }

    } else {
        echo "Usuario não encontrado.";
    }
}
?>