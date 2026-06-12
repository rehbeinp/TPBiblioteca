<html>

<head>
    <title>Login</title>
    <meta charset="utf-8">
</head>

<!-- Formulário de login enviado via método POST -->
<form method="POST">
    <h1>Login</h1>

    <!-- Campo para informar o usuário (e-mail) -->
    <label for='email'>Usuario</label>
    <input type="text" name="usuario" required>

    <!-- Campo para informar a senha -->
    <label for='senha'>Senha</label>
    <input type="password" name="senha" required> <br>

    <!-- Seleção do tipo de usuário -->
    <input type="radio" name="tipoUsusario" value="admin" /> Administrador
    <input type="radio" name="tipoUsusario" value="usuario" /> Usuário Padrão <br>

    <!-- Botão para enviar o formulário -->
    <button type='submit' name='btn_login'>Entrar</button>
</form>

</html>

<?php
// Inicia ou recupera a sessão atual
session_start();

// Importa funções auxiliares utilizadas pelo sistema
require_once "usuario/funcoes.php";

// Verifica se o botão de login foi pressionado
if (isset($_POST["btn_login"])) {

    // Recebe o usuário informado no formulário
    $USER = $_POST["usuario"];

    // Gera o hash SHA-256 da senha informada
    $SENHA = hash('sha256', (string)$_POST["senha"]);

    // Recebe o tipo de usuário selecionado
    $tipoUsuario = $_POST["tipoUsusario"];

<<<<<<< HEAD
    $con = mysqli_connect("127.0.0.1:3307", "root", "2004", "biblioteca", "3307");
=======
    // Realiza a conexão com o banco de dados
    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
>>>>>>> a85c5caab637b64c837b8c32fdd061ec61ac59b8

    // Verifica se houve erro na conexão
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    // Consulta para verificar se existe um usuário com os dados informados
    $sql = "SELECT email from usuarios where email='$USER' 
        and senha='$SENHA' and tipo = '$tipoUsuario'";

    // Executa a consulta SQL
    $exesql = mysqli_query($con, $sql);

    // Fecha a conexão com o banco de dados
    mysqli_close($con);

    // Conta quantas linhas foram retornadas pela consulta
    $num_linhas = mysqli_num_rows($exesql);

    // Se encontrar exatamente um usuário, o login é válido
    if ($num_linhas == 1) {

        // Armazena o usuário na sessão
        $_SESSION['status'] = $USER;

        // Exibe mensagem de sucesso
        echo "Usuario logado com sucesso";
        
        // Calcula a multa atualizada do usuário
        $multa_atualizada = calcula_multa($USER);

        // Atualiza a multa no banco de dados
        atualiza_multa($multa_atualizada);
        
        // Verifica se o usuário ainda está utilizando a senha padrão
        if($SENHA == hash('sha256',"1234")) {

<<<<<<< HEAD
        if ($tipoUsuario == "admin") {
            header("location: adimin/menu_admin.php");
        } else {
=======
            // Redireciona para a página de alteração de senha
            header("location: usuario/primeiro_login.php");

        }
        // Se for administrador, redireciona para o menu de administrador
        elseif ($tipoUsuario == "admin") {
            header("location: menu_adim.php");
        } 
        // Caso contrário, redireciona para o menu do usuário comum
        else {
>>>>>>> a85c5caab637b64c837b8c32fdd061ec61ac59b8
            header("location: usuario/menu_usuario.php");
        }

    } else {

        // Exibe mensagem caso o usuário não seja encontrado
        echo "Usuario não encontrado.";
    }
}
?>