
<?php
include "head_usuario.php";

session_start();
$num_linhas = 0;
$exesql_resultado  = '';
$mensagem = "";

$sql_todo_acervo = "SELECT 
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

function todoAcervo($sql){
    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $exesql_resultado = mysqli_query($con, $sql);
    
    mysqli_close($con);
    return $exesql_resultado;
}

if(isset($_SESSION['status']) and $_SESSION['status']!= "" ){
    $user = $_SESSION['status'];

    $exesql_resultado = todoAcervo($sql_todo_acervo);
    $num_linhas = mysqli_num_rows($exesql_resultado);

    if($num_linhas < 1){
       $mensagem = "<h3>[|]  Estamos nos abastecendo para você ;) [|] <3 </h3> <br> Em breve novidades";
    }
}
else{
    header("location: login.php");
}

function mensagem($objeto,$pesquisa, $num_linhas){
    if($num_linhas < 1){
        $mensagem = "<strong> Não escontramos nenhum Livro com esse(a) $objeto.</strong> Vamos tentar outro(a) $objeto?";
    }
    else {
        $mensagem = "Todos os resultados da pesquisa de '$pesquisa' em $objeto. <br><br>";
    }
    
    return $mensagem;
}

if(isset($_POST["btn_pesquisa"])){
    $objetoPesquisa = $_POST["objetoPesquisa"];
    $valorPesquisa = $_POST["valorPesquisa"];

    $sql_titulo = 
    "SELECT 
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
    where l.titulo like '%$valorPesquisa%'
    order by l.titulo";

    $sql_autor = 
    "SELECT 
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
    where l.autor like '%$valorPesquisa%'
    order by l.autor";

    $sql_ano = 
    "SELECT 
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
    where l.ano_publicacao = '$valorPesquisa'
    order by l.autor";

    $sql_classificacao = 
    "SELECT 
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
    where l.classificacao like '%$valorPesquisa%'
    order by l.classificacao";

    $sql_editora = 
    "SELECT 
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
    where l.editora like '%$valorPesquisa%'
    order by l.editora";

    if($objetoPesquisa == 'todos'){
        $exesql_resultado = todoAcervo($sql_todo_acervo);
        $num_linhas = mysqli_num_rows($exesql_resultado);
        $mensagem = "";
    }
    elseif($objetoPesquisa == 'título' and $valorPesquisa != ""){
        $exesql_resultado = todoAcervo($sql_titulo);
        $num_linhas = mysqli_num_rows($exesql_resultado);

        $mensagem = mensagem($objetoPesquisa, $valorPesquisa, $num_linhas);
        
    }
    elseif($objetoPesquisa == 'autor(a)'  and $valorPesquisa != ""){
        $exesql_resultado = todoAcervo($sql_autor);
        $num_linhas = mysqli_num_rows($exesql_resultado);
        $mensagem = mensagem($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    elseif($objetoPesquisa == 'ano'  and $valorPesquisa != ""){
        $exesql_resultado = todoAcervo($sql_ano);
        $num_linhas = mysqli_num_rows($exesql_resultado);
        $mensagem = mensagem($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    elseif($objetoPesquisa == 'classificação'  and $valorPesquisa != ""){
        $exesql_resultado = todoAcervo($sql_classificacao);
        $num_linhas = mysqli_num_rows($exesql_resultado);
        $mensagem = mensagem($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    elseif($objetoPesquisa == 'editora' and $valorPesquisa != ""){
        $exesql_resultado = todoAcervo($sql_editora);
        $num_linhas = mysqli_num_rows($exesql_resultado);
        $mensagem = mensagem($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    elseif($valorPesquisa == ""){
        $mensagem = "<p> <strong> Ops.. Parece que você esqueceu de preencher o texto da pesquisa. </strong> Adicione um texto para melhores resultados. :) </p>";
    }
    else{
        $mensagem = "<p>Selecione uma das opções de busca acima para melhores resultados em sua pesquisa. :) </p>";
    }

}

?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <form  method='POST'>
    <br>
    *******************************************************************************<br>
    <strong> Pesquisar em Nosso Acervo:</strong>
    <p><input type='text' name='valorPesquisa'></p>
    <p>
        <input type="radio" id="titulo" name="objetoPesquisa" value="título">
        <label for="titulo"> Título</label>
        <input type="radio" id="autor" name="objetoPesquisa" value="autor(a)">
        <label for="autor">Autor(a)</label>
        <input type="radio" id="ano" name="objetoPesquisa" value="ano">
        <label for="ano">Ano</label>
        <input type="radio" id="classificacao" name="objetoPesquisa" value="classificação" >
        <label for="classificacao">Classificação</label>
        <input type="radio" id="editora" name="objetoPesquisa" value="editora">
        <label for="editora">Editora</label>
        <input type="radio" id="todos" name="objetoPesquisa" value="todos" required>
        <label for="todos">Limpar Pesquisa</label>
        <button type='submit' name='btn_pesquisa'> Buscar </button> 
    *******************************************************************************<br>
    </p>
    <h2>Nosso acervo de livros: ADICIONAR ORDENAÇÃO</h2> 
        <div>
           <?php 
           if($mensagem != ""){
            echo $mensagem;
           }
           if ($num_linhas >= 1) {
            

            while ($resul = mysqli_fetch_assoc($exesql_resultado)) {
                echo "______________________________________________________________________________ <br> <br>";

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
            }?>
        </div>
	</form>

</body>
</html>