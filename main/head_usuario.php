
<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <form  method='POST'>
		<button type='submit' name='btn_logout'>Sair</button> 
    <p> <button type='submit' name='btn_home'>Menu Principal</button> 
        <button type='submit' name='btn_listar_livros'>Acervo de livros</button>
        <button type='submit' name='btn_listar_historico'>Histórico de retiradas</button>
        <button type='submit' name='btn_multas'>Minhas multas</button>
        <button type='submit' name='btn_conta_user'>Minha conta</button>
    </p>
	</form>
</body>
</html>


<?php

if(isset($_POST["btn_logout"])){
    unset($_SESSION['status']);
    header("location: login.php");
}

if(isset($_POST["btn_listar_historico"])){
    unset($_SESSION['status']);
    header("location: historico_retiradas.php");
}

if(isset($_POST["btn_listar_livros"])){
    unset($_SESSION['status']);
    header("location: todos_livros.php");
}

if(isset($_POST["btn_home"])){
    unset($_SESSION['status']);
    header("location: menu_usuario.php");
}

if(isset($_POST["btn_multas"])){
    unset($_SESSION['status']);
    header("location: menu_multas.php");
}

if(isset($_POST["btn_conta_user"])){
    unset($_SESSION['status']);
    header("location: conta_usuario.php");
}
?>