<?php
include "head_admin.php";

if(!isset($_SESSION['status'])){
    header("location: ../login.php");
}
?>

<h2>Área Administrativa</h2>

<p>Bem-vindo administrador.</p>

<ul>
    <li>Gerenciar usuários</li>
    <li>Gerenciar livros</li>
    <li>Visualizar relatórios</li>
</ul>