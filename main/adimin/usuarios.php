<?php
include "head_admin.php";

$con = mysqli_connect("localhost", "root", "2004", "biblioteca", "3307");

$sql = "SELECT id,nome,email,cpf FROM usuarios";

$resultado = mysqli_query($con,$sql);
?>

<h2>Usuários cadastrados</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>CPF</th>
</tr>

<?php
while($u = mysqli_fetch_assoc($resultado)){
    echo "<tr>";
    echo "<td>".$u['id']."</td>";
    echo "<td>".$u['nome']."</td>";
    echo "<td>".$u['email']."</td>";
    echo "<td>".$u['cpf']."</td>";
    echo "</tr>";
}
?>
</table>