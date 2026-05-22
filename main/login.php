<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
</head>
<form method="POST">
    <h1>Login</h1>
    <label for='email'>Usuario</label>
    <input type="text" name="usuario">

    <label for='senha'>Senha</label>
    <input type="password" name="senha"> <br>
    <input type="radio" name="tipoUsusario" value="admin" /> Administrador
    <input type="radio" name="tipoUsusario" value="usuario" /> Usuário Padrão <br>

    <button type='submit' name='btn_login'>Entrar</button>
</form>

</html>

<?php
session_start();

if (isset($_POST["btn_login"])) {
    $USER = $_POST["usuario"];
    $SENHA = $_POST["senha"];
    $tipoUsuario = $_POST["tipoUsusario"];

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }
    $sql = "SELECT email from usuarios where email='$USER'
            and senha='$SENHA' and tipo = '$tipoUsuario'";

    $exesql = mysqli_query($con, $sql);

    $num_linhas = mysqli_num_rows($exesql);

    if ($num_linhas == 1) {
        $_SESSION['status'] = $USER;

        echo "Usuario logado com sucesso";

        if ($tipoUsuario == "admin") {
            header("location: menu_adim.php"); ## alterar o arquivo do menu de admin

        } else {
            header("location: menu_usuario.php");
        }

    } else
        echo "Usuario nao encontrado";
}
?>