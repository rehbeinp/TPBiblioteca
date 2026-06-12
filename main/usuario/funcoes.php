<?php
// Inclui arquivo que gera a conexão
include "../conexao.php";

/**
 * Calcula multas de usuários com base em atrasos
 */
function calcula_multa($usuario){

    // Conexão com banco de dados pela função
    $con = cria_conexao();

    /**
     * Consulta:
     * - Calcula dias de atraso
     * - Multiplica por 0.75 para gerar valor da multa
     * - Verifica se já existe multa para o empréstimo
     */
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

    // Executa consulta
    $exesql = mysqli_query($con, $sql);

    // Fecha conexão
    mysqli_close($con);

    // Conta resultados
    $num_linhas = mysqli_num_rows($exesql);

    // Retorna resultados se houver multas
    if ($num_linhas >= 1){
        return $exesql;
    }
    else return null;
}

/**
 * Atualiza ou insere multas no banco de dados
 */
function atualiza_multa($multas){

    // Só executa se houver dados
    if($multas != null){

        // Conexão com banco pela função
        $con = cria_conexao();

        // Percorre todas as multas calculadas
        while ($resul = mysqli_fetch_assoc($multas)){

            $valor_multa = $resul['valor_multa'];
            $id_emprestimo = $resul['id_emprestimo'];

            // Se já existe multa, atualiza
            if($resul['flg_multa_existente'] == 'S'){

                $sql_multa = "UPDATE multas 
                set valor = '$valor_multa',
                ultima_atualizacao = now()
                where emprestimo_id = '$id_emprestimo'";
            }

            // Caso contrário, insere nova multa
            else{
                $sql_multa = "INSERT INTO multas (valor, motivo, paga, emprestimo_id, ultima_atualizacao)
                VALUES ('$valor_multa', 'atraso', '0', ' $id_emprestimo', now())";
            }
            
            // Executa query de update/insert
            mysqli_query($con, $sql_multa);
        }

        // Fecha conexão
        mysqli_close($con);
    }
}

/**
 * Filtra uma tabela (array) por busca textual
 */
function pesquisa($tabela, $coluna, $valor){

    $tabela_filtrada = [];

    // Percorre cada linha da tabela
    foreach($tabela as $linha){

        // Busca parcial (case insensitive)
        if(str_contains(
            strtolower(trim($linha[$coluna])),
            strtolower(trim($valor))
        )){
            $tabela_filtrada[] = $linha;
        }
    }

    return $tabela_filtrada;
}

/**
 * Ordena tabela em ordem crescente por coluna
 */
function ordenaCrescente($tabela, string $coluna){

    usort($tabela, function($a, $b) use ($coluna) {
        return $a[$coluna] <=> $b[$coluna];
    });

    return $tabela;
}

/**
 * Ordena tabela em ordem decrescente por coluna
 */
function ordenaDecrescente($tabela, string $coluna){

    usort($tabela, function($a, $b) use ($coluna) {
        return $b[$coluna] <=> $a[$coluna];
    });

    return $tabela;
}

/**
 * Gera mensagem de retorno para pesquisas
 */
function mensagem_pesquisa($objeto,$pesquisa, $num_linhas){

    if($num_linhas < 1){
        $mensagem = "<strong> Não escontramos nenhum Livro com esse(a) $objeto.</strong> Vamos tentar outro(a) $objeto?";
    }
    else {
        $mensagem = "Todos os resultados da pesquisa de '$pesquisa' em $objeto. <br><br>";
    }
    
    return $mensagem;
}

/**
 * Executa update genérico no banco e retorna sucesso/falha
 */
function atualizar_dado($sql){

    // Conexão com banco pela função
    $con = cria_conexao();

    // Executa query
    mysqli_query($con, $sql);

    // Verifica quantas linhas foram afetadas
    $linhas = mysqli_affected_rows($con);

    // Fecha conexão
    mysqli_close($con);
    
    // Retorna true se houve alteração no banco
    if($linhas > 0){
        return true;
    }
    else {
        return false;
    }
}

?>