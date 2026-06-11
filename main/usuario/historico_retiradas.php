
<?php
include "head_usuario.php";
require_once "funcoes.php";

session_start();
$num_linhas = 0;
$exesql_retirados  = '';
$mensagem = "";
$todo_historico = [];
$mostra = [];

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
                e.data_devolucao,
                e.data_devolucao_prevista,
                m.valor as valor_multa,
                m.motivo as motivo_multa,
                m.paga as multa_paga
            from emprestimos e
            inner join usuarios u on e.usuario_id = u.id
            inner join livros l on e.livro_id = l.id
            left join multas m on m.emprestimo_id = e.id
            where u.email='$user'
            order by e.data_retirada desc, e.data_devolucao_prevista desc";

    $exesql_retirados = mysqli_query($con, $sql_retirados);
    mysqli_close($con);

    $num_linhas = mysqli_num_rows($exesql_retirados);

    if($num_linhas < 1){
       $mensagem = "<h3>[|]  :)> Retire um livro!! :)>  [|]  </h3>";
    }else{
        while ($resul = mysqli_fetch_assoc($exesql_retirados)){
            $todo_historico[] = 
                ["titulo" => $resul['titulo'], 
                "autor"=> $resul['autor'], 
                "classificacao"=> $resul['classificacao'], 
                "data_retirada" => $resul['data_retirada'], 
                "data_devolucao_prevista"=> $resul['data_devolucao_prevista'], 
                "valor_multa" => $resul['valor_multa'],
                "motivo_multa" => $resul['motivo_multa'],
                "multa_paga" => $resul['multa_paga'], 
                "data_devolucao"=> $resul['data_devolucao']];
        }
        $mostra = $todo_historico;
    }

}
else{
    header("location: ../login.php");
}

if(isset($_POST['btn_pesquisa'])){
    $valorPesquisa = $_POST['valorPesquisa'];
    $objetoPesquisa = $_POST['objetoPesquisa'];

    if($objetoPesquisa == 'todos'){
        $mostra = $todo_historico;
        $mensagem = "";
    }
    elseif($valorPesquisa != ""){
        $mostra = pesquisa($todo_historico, $objetoPesquisa, $valorPesquisa);
        $num_linhas = count($mostra);
        $mensagem = mensagem_pesquisa($objetoPesquisa, $valorPesquisa, $num_linhas);
    }
    else{
        $mensagem = "<p> <strong> Ops.. Parece que você esqueceu de preencher o texto da pesquisa. </strong> Adicione um texto para melhores resultados. :) </p>";
    }
}

if(isset($_POST["btn_ordena_crescente"])){
    $objetoOrdenacao = $_POST["objetoOrdenacao"];

    if (isset($_POST["valorPesquisa"]) && $_POST["valorPesquisa"] != "" && $_POST["objetoPesquisa"] != "todos") {
        $mostra = pesquisa($todo_historico, $_POST["objetoPesquisa"], $_POST["valorPesquisa"]);
    }
    $mostra = ordenaCrescente($mostra, $objetoOrdenacao);
}

if(isset($_POST["btn_ordena_decrescente"])){
    $objetoOrdenacao = $_POST["objetoOrdenacao"];

    if (isset($_POST["valorPesquisa"]) && $_POST["valorPesquisa"] != "" && $_POST["objetoPesquisa"] != "todos") {
        $mostra = pesquisa($todo_historico, $_POST["objetoPesquisa"], $_POST["valorPesquisa"]);
    }
    $mostra = ordenaDecrescente($mostra, $objetoOrdenacao);
}

?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
</head>
<body>
    <br>
    <form method='POST'>
    *******************************************************************************<br>
    <strong> Pesquisar no histórico de retiradas:</strong>
    <p><input type='text' name='valorPesquisa'></p>
    <p>
        <input type="radio" id="titulo" name="objetoPesquisa" value="titulo">
        <label for="titulo"> Titulo</label>
        <input type="radio" id="autor" name="objetoPesquisa" value="autor">
        <label for="autor">Autor</label>
        <input type="radio" id="classificacao" name="objetoPesquisa" value="classificacao" >
        <label for="classificacao">Classificacao</label>
        <input type="radio" id="todos" name="objetoPesquisa" value="todos" required>
        <label for="todos">Limpar Pesquisa</label>
        <button type='submit' name='btn_pesquisa'> Buscar </button>
     <br>
    </form>
    ======================================================================<br><br>
    <form  method='POST'>
    <strong> Ordenar Livros do histórico:</strong> <br><br>
        <input type="radio" id="titulo" name="objetoOrdenacao" value="titulo">
        <label for="tituloOrd"> Titulo</label>
        <input type="radio" id="autor" name="objetoOrdenacao" value="autor">
        <label for="autorORD">Autor</label>
        <input type="radio" id="classificacao" name="objetoOrdenacao" value="classificacao" >
        <label for="classificacaoOrd">Classificacao</label>
        <input type="radio" id="data_retiradaOrd" name="objetoOrdenacao" value="data_retirada">
        <label for="data_retiradaOrd">Data de retirada</label><br>
        <input type="radio" id="data_devolucao_prevista" name="objetoOrdenacao" value="data_devolucao_prevista">
        <label for="data_devolucao_previstaOrd">Data de devolução prevista</label>
        <input type="radio" id="data_devolucao" name="objetoOrdenacao" value="data_devolucao" required>
        <label for="data_devolucaoOrd">Data de devolução</label>
        <input type="radio" id="valor_multa" name="objetoOrdenacao" value="valor_multa" required>
        <label for="valor_multaOrd">Multa</label>
        <input type="hidden" name="valorPesquisa" value="<?= $_POST['valorPesquisa'] ?? '' ?>">
        <input type="hidden" name="objetoPesquisa" value="<?= $_POST['objetoPesquisa'] ?? 'todos' ?>"><br>

        <button type='submit' name='btn_ordena_crescente'> Ordena Crescente </button>
        <button type='submit' name='btn_ordena_decrescente'> Ordena Decrescente </button> <br>
    </form>
    *******************************************************************************<br>
    <br>
    <h2>Bem-Vindo ao Histórico de Retiradas</h2> 
    <form  method='POST'>
        <div>
           <?php 
            echo "$mensagem";
            if ($num_linhas >= 1) {
                echo "<h3>Livros que você já retirou:</h3>";
                foreach($mostra as $resul) {
                    echo "___________________________________________________________ <br> <br>";
                    echo "<strong>Título: </strong>" . $resul['titulo']. " <br>";
                    echo "<strong>Autor(a): </strong>" . $resul['autor']. " <br>";
                    echo "<strong>Classificação: </strong>" . $resul['classificacao']. " <br>";
                    echo "<strong>Data da Retirada: </strong>" . $resul['data_retirada']. " <br>";
                    echo "<strong>Data da Devolução Prevista: </strong>" . $resul['data_devolucao_prevista']. " <br>";
                    if( $resul['data_devolucao'] != null){
                        echo "<strong>Data da Devolução: </strong>" . $resul['data_devolucao']. " <br>";
                    }
                    if( $resul['valor_multa'] == null){
                        echo "<strong>Multa: </strong> Sem multa. :) <br><br>";
                    }
                    else{
                        echo" - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br>";
                        if($resul['multa_paga'] == 1) {echo "<strong> Multa quitada :) </strong> <br>";}
                        else{ echo "<strong> Multa em haver :( </strong> <br> Venha pagar para não aumentar ;) <br>";}
                        echo "<strong>Valor multa: </strong> R$" . $resul['valor_multa']. " <br>";
                        echo "<strong>Motivo da Multa: </strong>" . $resul['motivo_multa']. "  <br><br> ";
                        
                    }
                }
            }
            ?>
        </div>
	</form>

</body>
</html>