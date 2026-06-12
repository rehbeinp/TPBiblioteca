<?php

$con = mysqli_connect("localhost", "root", "2004", "biblioteca", "3306");

if ($con) {
    echo "CONECTOU";
} else {
    echo "ERRO: " . mysqli_connect_error();
}