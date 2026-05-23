
<?php
include "head_usuario.php";

session_start();
$num_linhas = 0;
$exesql_retirados  = '';
if($_SESSION['status'] != "" ){
    $user = $_SESSION['status'];

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql_retirados = "SELECT 
                l.titulo,
                l.autor,
                l.classificacao,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                l.numero_paginas,
                l.assunto,
                l.disponivel
            from livros l 
            order by l.titulo";

    $exesql_retirados = mysqli_query($con, $sql_retirados);
    $num_linhas = mysqli_num_rows($exesql_retirados);

}
else{
    header("location: login.php");
}

if(isset($_POST["btn_logout"])){
    unset($_SESSION['status']);
    header("location: login.php");
}


?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <h2>Nosso acervo de livros:</h2> 
    <p> <input type='text' name='livro'>
        <button type='submit' name='btn_home'>Buscar Livro</button> </p>
        <p>PRECISA CONCLUIR</p>
    <form  method='POST'>
        <div>
           <?php 
           if ($num_linhas >= 1) {

            while ($resul = mysqli_fetch_assoc($exesql_retirados)) {

                echo "<strong>Título: </strong>" . $resul['titulo']. " <br>";
                echo "<strong>Autor(a): </strong>" . $resul['autor']. " <br>";
                echo "<strong>Classificação: </strong>" . $resul['classificacao']. " <br>";
                echo "<strong>Editora: </strong>" . $resul['editora']. " <br>";
                echo "<strong>Edição: </strong>" . $resul['edicao']. " <br>";
                echo "<strong>Ano Públicação: </strong>" . $resul['ano_publicacao']. " <br>";
                echo "<strong>N° de páginas: </strong>" . $resul['numero_paginas']. " <br>";
                echo "<strong>Assunto: </strong>" . $resul['assunto']. " <br>";
                if( $resul['disponivel'] == 0){
                    echo "<strong> Não está disponível no momento :( </strong> <br><br>";
                }
                else echo "<strong> Esperando por um leitor :) </strong> <br><br>";
            }
    

            } else
                echo "<h3>[|]  Estamos nos abastecendo para você ;) [|] <3 </h3> <br> Em breve novidades";
                ?>
        </div>
	</form>

</body>
</html>