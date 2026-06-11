
<?php
include "head_usuario.php";
require_once "funcoes.php";

session_start();
$num_linhas = 0;
$exesql_resultado  = '';
$mensagem = "";
$todo_acervo = [];
$mostra = [];

function todoAcervo(){
    $sql_todo_acervo = "SELECT 
        l.titulo,
        l.autor,
        l.classificacao,
        l.editora,
        l.edicao,
        l.ano_publicacao as ano,
        l.numero_paginas as paginas,
        l.assunto,
        l.disponivel
    from livros l 
    order by l.titulo";

    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $exesql_resultado = mysqli_query($con, $sql_todo_acervo);
    
    mysqli_close($con);
    return $exesql_resultado;
}


if(isset($_SESSION['status']) and $_SESSION['status']!= "" ){
    $user = $_SESSION['status'];

    $exesql_todo_acervo = todoAcervo();
    $num_linhas = mysqli_num_rows($exesql_todo_acervo);

    if($num_linhas < 1){
       $mensagem = "<h3>[|]  Estamos nos abastecendo para você ;) [|] <3 </h3> <br> Em breve novidades";
    }
    else{
        while ($resul = mysqli_fetch_assoc($exesql_todo_acervo)){
            $todo_acervo[] = ["titulo" => $resul['titulo'], "autor"=> $resul['autor'], 
            "classificacao"=> $resul['classificacao'], "editora" => $resul['editora'], 
            "edicao"=> $resul['edicao'], "ano" => $resul['ano'], "numero_paginas"=> $resul['numero_paginas'],
            "assunto" => $resul['assunto'], "disponivel"=> $resul['disponivel']];
        }
        $mostra = $todo_acervo;
    }

}
else{
    header("location: ../login.php");
}

if(isset($_POST["btn_pesquisa"])){
    $objetoPesquisa = $_POST["objetoPesquisa"];
    $valorPesquisa = $_POST["valorPesquisa"];

    if($objetoPesquisa == 'todos'){
        $mostra = $todo_acervo;
        $mensagem = "";
    }
    elseif($valorPesquisa != ""){
        $mostra = pesquisa($todo_acervo,$objetoPesquisa, $valorPesquisa);
        $num_linhas = count($mostra);
        $mensagem = mensagem_pesquisa($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    else{
        $mensagem = "<p> <strong> Ops.. Parece que você esqueceu de preencher o texto da pesquisa. </strong> Adicione um texto para melhores resultados. :) </p>";
    }
}

if(isset($_POST["btn_ordena_crescente"])){
    $objetoOrdenacao = $_POST["objetoOrdenacao"];
    $mostra = $todo_acervo;

    if (isset($_POST["valorPesquisa"]) && $_POST["valorPesquisa"] != "" && $_POST["objetoPesquisa"] != "todos") {
        $mostra = pesquisa($mostra, $_POST["objetoPesquisa"], $_POST["valorPesquisa"]);
    }
    $mostra = ordenaCrescente($mostra, $objetoOrdenacao);
}

if(isset($_POST["btn_ordena_decrescente"])){
    $objetoOrdenacao = $_POST["objetoOrdenacao"];
    $mostra = $todo_acervo;

    if (isset($_POST["valorPesquisa"]) && $_POST["valorPesquisa"] != "" && $_POST["objetoPesquisa"] != "todos") {
        $mostra = pesquisa($mostra, $_POST["objetoPesquisa"], $_POST["valorPesquisa"]);
    }
    $mostra = ordenaDecrescente($mostra, $objetoOrdenacao);
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
        <input type="radio" id="titulo" name="objetoPesquisa" value="titulo">
        <label for="titulo"> Titulo</label>
        <input type="radio" id="autor" name="objetoPesquisa" value="autor">
        <label for="autor">Autor</label>
        <input type="radio" id="ano" name="objetoPesquisa" value="ano">
        <label for="ano">Ano</label>
        <input type="radio" id="classificacao" name="objetoPesquisa" value="classificacao" >
        <label for="classificacao">Classificacao</label>
        <input type="radio" id="editora" name="objetoPesquisa" value="editora">
        <label for="editora">Editora</label>
        <input type="radio" id="todos" name="objetoPesquisa" value="todos" required>
        <label for="todos">Limpar Pesquisa</label>
        <button type='submit' name='btn_pesquisa'> Buscar </button>
     <br>
    </form>
    ======================================================================<br><br>
    <form  method='POST'>
    <strong> Ordenar Livros em Nosso Acervo:</strong> <br><br>
        <input type="radio" id="titulo" name="objetoOrdenacao" value="titulo">
        <label for="tituloOrd"> Titulo</label>
        <input type="radio" id="autor" name="objetoOrdenacao" value="autor">
        <label for="autorORD">Autor</label>
        <input type="radio" id="ano" name="objetoOrdenacao" value="ano">
        <label for="anoOrd">Ano</label>
        <input type="radio" id="classificacao" name="objetoOrdenacao" value="classificacao" >
        <label for="classificacaoOrd">Classificacao</label>
        <input type="radio" id="editoraOrd" name="objetoOrdenacao" value="editora">
        <label for="editoraOrd">Editora</label>
        <input type="radio" id="edicao" name="objetoOrdenacao" value="edicao">
        <label for="edicaoOrd">Edicao</label>
        <input type="radio" id="paginas" name="objetoOrdenacao" value="paginas" required>
        <label for="paginasOrd">N° páginas</label>
        <input type="hidden" name="valorPesquisa" value="<?= $_POST['valorPesquisa'] ?? '' ?>">
        <input type="hidden" name="objetoPesquisa" value="<?= $_POST['objetoPesquisa'] ?? 'todos' ?>"><br>

        <button type='submit' name='btn_ordena_crescente'> Ordena Crescente </button>
        <button type='submit' name='btn_ordena_decrescente'> Ordena Decrescente </button> <br>
    </form>
    *******************************************************************************<br>


    </p>
    <h2>Nosso acervo de livros:</h2> 
        <div>
           <?php 

            echo $mensagem;

           if ($num_linhas >= 1) {
            
            foreach($mostra as $resul) {
                echo "______________________________________________________________________________ <br> <br>";
                echo "<strong>Titulo: </strong>" . $resul['titulo']. " <br>";
                echo "<strong>Autor: </strong>" . $resul['autor']. " <br>";
                echo "<strong>Classificacao: </strong>" . $resul['classificacao']. " <br>";
                echo "<strong>Editora: </strong>" . $resul['editora']. " <br>";
                echo "<strong>Edição: </strong>" . $resul['edicao']. " <br>";
                echo "<strong>Ano Públicação: </strong>" . $resul['ano']. " <br>";
                echo "<strong>N° de páginas: </strong>" . $resul['paginas']. " <br>";
                echo "<strong>Assunto: </strong>" . $resul['assunto']. " <br>";
                if( $resul['disponivel'] == 0){
                    echo "<strong> Não está disponível no momento :( </strong> <br><br>";
                }
                else echo "<strong> Esperando por um leitor :) </strong> <br><br>";
            }
            }
            ?>
        </div>
	</form>

</body>
</html>