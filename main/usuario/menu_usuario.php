
<?php
include "head_usuario.php";

session_start();
$num_linhas = 0;
$exesql_retirados  = '';
if(isset($_SESSION['status']) and $_SESSION['status']!= "" ){
    $user = $_SESSION['status'];

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql_retirados = "SELECT 
                l.titulo,
                l.autor,
                l.classificacao,
                e.data_retirada,
                e.data_devolucao_prevista,
                m.valor as valor_multa,
                m.motivo as motivo_multa,
                m.paga as multa_paga
            from emprestimos e
            inner join usuarios u on e.usuario_id = u.id
            inner join livros l on e.livro_id = l.id
            left join multas m on m.emprestimo_id = e.id
            where u.email='$user' and e.data_devolucao is null
            order by e.data_retirada desc";

    $exesql_retirados = mysqli_query($con, $sql_retirados);
    mysqli_close($con);
    $num_linhas = mysqli_num_rows($exesql_retirados);

}
else{
   header("location: ../login.php");
}
?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <h2>Bem-Vindo ao Menu Principal da Bibioteca</h2> 
    <form  method='POST'>
        <div>
           <?php 
           if ($num_linhas >= 1) {
            echo "<h3>Livros com você no momento:</h3>";
            while ($resul = mysqli_fetch_assoc($exesql_retirados)) {

                echo "<strong>Título: </strong>" . $resul['titulo']. " <br>";
                echo "<strong>Autor(a): </strong>" . $resul['autor']. " <br>";
                echo "<strong>Classificação: </strong>" . $resul['classificacao']. " <br>";
                echo "<strong>Data da Retirada: </strong>" . $resul['data_retirada']. " <br>";
                echo "<strong>Data da Devolução Prevista: </strong>" . $resul['data_devolucao_prevista']. " <br>";
                if( $resul['valor_multa'] == null){
                    echo "<strong> Multa: </strong> Sem multa. :) <br><br>";
                }
                else{
                    echo "<strong>Valor multa: </strong> R$" . $resul['valor_multa']. " <br>";
                    echo "<strong>Motivo dsa Multa: </strong>" . $resul['motivo_multa']. " <br>";
                    if($resul['paga'] == 0) echo "<strong> Multa quitada :) </strong> <br><br>";
                    else echo "<strong> Multa em haver :( </strong>  <p style='opacity: 0.5;'>Venha pagar para não aumentar ;)</p><br><br> ";
                }
            }
        

            } else
                echo "<h3>[|]  :)> Retire um livro!! :)>  [|]  </h3>";
                ?>
        </div>
	</form>

</body>
</html>