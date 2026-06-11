<?php
session_start();

if (!isset($_SESSION['status'])) {
    header("Location: ../login.php");
    exit;
}

require('../biblioteca/fpdf186/fpdf.php');

$user = $_SESSION['status'];

$con = mysqli_connect("localhost", "root", "123456", "biblioteca", "3306");

if (mysqli_connect_errno()) {
    die("Falhou devido a conexao com Mysql: " . mysqli_connect_error());
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
            cast(m.paga as SIGNED) as multa_paga
        from emprestimos e
        inner join usuarios u on e.usuario_id = u.id
        inner join livros l on e.livro_id = l.id
        inner join multas m on m.emprestimo_id = e.id
        where u.email='$user'
        order by m.paga asc, e.data_retirada desc, e.data_devolucao_prevista desc";

$resultado_sql = mysqli_query($con, $sql_retirados);

if (!$resultado_sql) {
    die(mysqli_error($con));
}

$num_linhas = mysqli_num_rows($resultado_sql);

if ($num_linhas > 0) {

    // Página horizontal (Landscape)
    $pdf = new FPDF('L', 'mm', 'A4');

    $pdf->AddPage();

    $data = date('d-m-Y');

    $titulo = "Relatório de Multas $data";

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');

    $pdf->Ln(3);

    // Fonte menor para caber na página
    $pdf->SetFont('Arial', 'B', 8);

    $pdf->Cell(25, 8, utf8_decode('Título'), 1);
    $pdf->Cell(35, 8, 'Autor', 1);
    $pdf->Cell(50, 8, utf8_decode('Class.'), 1);
    $pdf->Cell(25, 8, 'Retirada', 1);
    $pdf->Cell(25, 8, 'Dev. Prev.', 1);
    $pdf->Cell(25, 8, utf8_decode('Devolução'), 1);
    $pdf->Cell(30, 8, 'Status', 1);
    $pdf->Cell(20, 8, 'Valor', 1);
    $pdf->Cell(25, 8, 'Motivo', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 7);

    while ($resul = mysqli_fetch_assoc($resultado_sql)) {

        $status = ($resul['multa_paga'] == 1)
            ? 'Multa Paga'
            : 'Multa em haver';

        $devolucao = ($resul['data_devolucao'] == null)
            ? utf8_decode('Não devolvido')
            : $resul['data_devolucao'];

        $pdf->Cell(25, 8, utf8_decode($resul['titulo']), 1);
        $pdf->Cell(35, 8, utf8_decode($resul['autor']), 1);
        $pdf->Cell(50, 8, utf8_decode($resul['classificacao']), 1);
        $pdf->Cell(25, 8, $resul['data_retirada'], 1);
        $pdf->Cell(25, 8, $resul['data_devolucao_prevista'], 1);
        $pdf->Cell(25, 8, $devolucao, 1);
        $pdf->Cell(30, 8, $status, 1);
        $pdf->Cell(20, 8, 'R$ ' . $resul['valor_multa'], 1);
        $pdf->Cell(25, 8, utf8_decode($resul['motivo_multa']), 1);

        $pdf->Ln();
    }

    mysqli_close($con);

    $nome_arquivo = "multas_$data.pdf";

    $pdf->Output('D', $nome_arquivo);
    exit;
}

mysqli_close($con);

echo "Nenhuma multa encontrada.";
?>