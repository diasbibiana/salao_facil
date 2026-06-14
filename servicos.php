<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_POST['salvar'])){

    $nome = $_POST['nome'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO servicos(nome,valor)
            VALUES('$nome','$valor')";

    $conexao->query($sql);

    echo "<p>Serviço cadastrado!</p>";
}

?>

<h1>Cadastro de Serviços</h1>

<form method="POST">

Nome do Serviço:<br>
<input type="text" name="nome" required><br><br>

Valor:<br>
<input type="number" step="0.01" name="valor" required><br><br>

<button type="submit" name="salvar">
Salvar
</button>

</form>

<hr>

<h2>Serviços Cadastrados</h2>

<?php

$resultado = $conexao->query("SELECT * FROM servicos");

while($servico = $resultado->fetch_assoc()){

    echo $servico['nome']." - R$ ".$servico['valor']."<br>";

}

?>

<br><br>

<a href="dashboard.php">Voltar</a>