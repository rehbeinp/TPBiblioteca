<?php
function calcula_multa($usuario){
    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    $sql = 
    "SELECT e.id as id_emprestimo, 
    DATEDIFF( date(now()), date(e.data_devolucao_prevista)) * 0.75 as valor_multa,
    case when e.id = m.emprestimo_id then 'S' else 'N' end as flg_multa_existente
    FROM emprestimos e
    inner join usuarios u on u.id = e.usuario_id
    left join multas m on m.emprestimo_id = e.id
    where e.data_devolucao is null
        and u.email = '$usuario'
        and date(e.data_devolucao_prevista) < date(now())
        and date(m.ultima_atualizacao) < date(now())";

    $exesql = mysqli_query($con, $sql);
    mysqli_close($con);

    $num_linhas = mysqli_num_rows($exesql);

    if ($num_linhas >= 1){
        return $exesql;
    }
    else return null;
}


function atualiza_multa($multas){
    if($multas != null){
        $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

        if (mysqli_connect_errno()) {
            echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
        }

        while ($resul = mysqli_fetch_assoc($multas)){
            $valor_multa = $resul['valor_multa'];
            $id_emprestimo = $resul['id_emprestimo'];

            if($resul['flg_multa_existente'] == 'S'){
                $sql_multa = "UPDATE multas 
                set valor = '$valor_multa',
                ultima_atualizacao = now()
                where emprestimo_id = '$id_emprestimo'";
            }
            else{
                $sql_multa = "INSERT INTO multas (valor, motivo, paga, emprestimo_id, ultima_atualizacao)
                VALUES ('$valor_multa', 'atraso', '0', ' $id_emprestimo', now())";
            }
            
            mysqli_query($con, $sql_multa);
        
        }
        mysqli_close($con);
    }
}

function pesquisa($tabela, $coluna, $valor){
    $tabela_filtrada = [];

    foreach($tabela as $linha){
        if(str_contains(
            strtolower(trim($linha[$coluna])),
            strtolower(trim($valor))
        )){
            $tabela_filtrada[] = $linha;
        }
    }
    return $tabela_filtrada;
}

function ordenaCrescente($tabela, string $coluna){
    usort($tabela, function($a, $b) use ($coluna) {
        return $a[$coluna] <=> $b[$coluna];
    });
    return $tabela;
}

function ordenaDecrescente($tabela, string $coluna){
    usort($tabela, function($a, $b) use ($coluna) {
        return $b[$coluna] <=> $a[$coluna];
    });
    return $tabela;
}

function mensagem_pesquisa($objeto,$pesquisa, $num_linhas){
    if($num_linhas < 1){
        $mensagem = "<strong> Não escontramos nenhum Livro com esse(a) $objeto.</strong> Vamos tentar outro(a) $objeto?";
    }
    else {
        $mensagem = "Todos os resultados da pesquisa de '$pesquisa' em $objeto. <br><br>";
    }
    
    return $mensagem;
}

function atualizar_dado($sql){
    $con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");
    if (mysqli_connect_errno()) {
        echo "Falhou devido a conexao com Mysql:" . mysqli_connect_error();
    }

    mysqli_query($con, $sql);
    $linhas = mysqli_affected_rows($con);
    mysqli_close($con);
    
    if($linhas > 0){
        return true;
    }
    else {
        return false;
    }
}
?>