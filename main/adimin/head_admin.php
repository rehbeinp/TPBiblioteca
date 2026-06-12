<?php
session_start();

if(isset($_POST["btn_logout"])){
    unset($_SESSION['status']);
    header("location: ../login.php");
}

if(isset($_POST["btn_home"])){
    header("location: menu_admin.php");
}

if(isset($_POST["btn_usuarios"])){
    header("location: usuarios.php");
}

if(isset($_POST["btn_livros"])){
    header("location: livros.php");
}

if(isset($_POST["btn_relatorios"])){
    header("location: relatorio_admin.php");
}
?>

<form method="POST">
    <button type="submit" name="btn_home">Menu Principal</button>
    <button type="submit" name="btn_usuarios">Usuários</button>
    <button type="submit" name="btn_livros">Livros</button>
    <button type="submit" name="btn_relatorios">Relatórios</button>
    <button type="submit" name="btn_logout">Sair</button>
</form>