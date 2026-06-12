<?php
include "head_admin.php";

$con = mysqli_connect("localhost","root","2004","biblioteca","3307");

$sql = "SELECT * FROM livros";

$resultado = mysqli_query($con,$sql);
?>

<h2>Gerenciamento de Livros</h2>

<table border="1">
<tr>
    <th>Título</th>
    <th>Autor</th>
    <th>Editora</th>
    <th>Disponível</th>
</tr>

<?php
while($l = mysqli_fetch_assoc($resultado)){
    echo "<tr>";
    echo "<td>".$l['titulo']."</td>";
    echo "<td>".$l['autor']."</td>";
    echo "<td>".$l['editora']."</td>";
    echo "<td>".$l['disponivel']."</td>";
    echo "</tr>";
}
?>
</table>