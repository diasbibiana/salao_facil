<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_POST['salvar'])){
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $valor = $_POST['valor'];

    $conexao->query("
        INSERT INTO produtos(nome, quantidade, valor)
        VALUES('$nome', '$quantidade', '$valor')
    ");

    echo "<p>Produto cadastrado!</p>";
}
?>

<h1>Cadastro de Produtos</h1>

<form method="POST">
Nome:<br>
<input type="text" name="nome" required><br><br>

Quantidade:<br>
<input type="number" name="quantidade" required><br><br>

Valor:<br>
<input type="number" step="0.01" name="valor" required><br><br>

<button type="submit" name="salvar">Salvar</button>
</form>

<hr>

<h2>Produtos Cadastrados</h2>

<?php
$resultado = $conexao->query("SELECT * FROM produtos");

while($produto = $resultado->fetch_assoc()){
    echo $produto['nome']." - Estoque: ".$produto['quantidade']." - R$ ".$produto['valor']."<br>";
}
?>

<br><br>
<a href="dashboard.php">Voltar</a>