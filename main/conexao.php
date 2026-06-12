<?php
function cria_conexao(){
    // Realiza a conexão com o banco de dados
    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

    // Verifica se houve erro na conexão
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }
    return $con;
}
?>