<?php
include "head_admin.php";

$con = mysqli_connect("localhost","root","2004","biblioteca","3307");

$usuarios = mysqli_fetch_assoc(
    mysqli_query($con,"SELECT COUNT(*) total FROM usuarios")
);

$livros = mysqli_fetch_assoc(
    mysqli_query($con,"SELECT COUNT(*) total FROM livros")
);

$emprestimos = mysqli_fetch_assoc(
    mysqli_query($con,"SELECT COUNT(*) total FROM emprestimos")
);
?>

<h2>Relatório Geral</h2>

<p>Total de usuários: <?= $usuarios['total'] ?></p>

<p>Total de livros: <?= $livros['total'] ?></p>

<p>Total de empréstimos: <?= $emprestimos['total'] ?></p>