<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <h2>Bem-Vindo ao menu principal da Bibioteca</h2> 
    <form  method='POST'>
        <div>
            texto
        </div>
        <br>
		<button type='submit' name='btn_logout'>Sair</button>
	</form>

</body>
</html>
<?php
session_start();
if($_SESSION['status'] != "" ){
    $user = $_SESSION['status'];

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql = "SELECT 
                l.titulo,
                l.autor,
                l.classificacao,
                e.data_retirada,
                e.data_devolucao_prevista,
                e.data_devolucao
            from emprestimos e
            inner join usuarios u on e.usuario_id = u.id
            inner join livros l on e.livro_id = l.id
            where u.email='$user'";
    
    $exesql = mysqli_query($con, $sql);
    $num_linhas = mysqli_num_rows($exesql);

    if ($num_linhas >= 1) {
        $resul = mysqli_fetch_assoc($exesql);
        
        echo $resul['titulo']. " <br>";
        echo $resul['autor']. " <br>";
        echo $resul['classificacao']. " <br>";
        echo $resul['data_retirada']. " <br>";
        echo $resul['data_devolucao_prevista']. " <br>";
        echo $resul['data_devolucao']. " <br>";

    } else
        echo "Sem emprestimos de livros";

}
else{
    header("location: login.php");
}

if(isset($_POST["btn_logout"])){
    unset($_SESSION['status']);
    header("location: login.php");
}


?>