
<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <form  method='POST'>
		<button type='submit' name='btn_logout'>Sair</button> 
    <p> <button type='submit' name='btn_listar_livros'>Ver todos os livros</button>
        <button type='submit' name='btn_listar_historico'>Histórico de retiradas</button>
        <button type='submit' name='btn_home'>Voltar ao Menu Principal</button> 

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

?>