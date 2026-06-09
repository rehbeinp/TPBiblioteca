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
                nome,
                cpf,
                data_nascimento,
                endereco,
                email
            from usuarios
            where email='$user'";

    $exesql_retirados = mysqli_query($con, $sql_retirados);
    mysqli_close($con);
    $num_linhas = mysqli_num_rows($exesql_retirados);

}
else{
    header("location: ../login.php");
}

if(isset($_POST['btn_alterar'])){
    $_SESSION['nome'] = "alterar";
    $_SESSION['nascimento'] = "alterar";
    $_SESSION['endereco'] = "alterar";
    $_SESSION['email'] = "alterar";
    $_SESSION['senha'] = "alterar";
    header("location: alterar_dados.php");
}

?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <h2>Informações sobre a sua conta</h2> 
    <form  method='POST'>
        <div>
            <?php 
            if ($num_linhas >= 1) {
            echo "<h3>Seus dados cadastrados:</h3>";
                while ($resul = mysqli_fetch_assoc($exesql_retirados)) {
                    echo "<strong>Nome: </strong>" . $resul['nome']. "<br> <br>";
                    echo "<strong>Data Nascimento: </strong>" . $resul['data_nascimento']. "<br> <br>";
                    echo "<strong>Endereço: </strong>" . $resul['endereco']. "<br> <br>";
                    echo "<strong>Email: </strong>" . $resul['email']. "<br> <br>";
                    echo "<strong>CPF/CNPJ: </strong>" . $resul['cpf']. "<br> <br>";
                }
            }
            else{echo "Não encontramos seus dados";}
            ?>
            <button type='submit' name='btn_alterar'> Atualizar dados </button> 
        </div>
	</form>

</body>
</html>