<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_POST['salvar'])){
    $cliente_id = $_POST['cliente_id'];
    $servico_id = $_POST['servico_id'] ?: "NULL";
    $produto_id = $_POST['produto_id'] ?: "NULL";
    $quantidade = $_POST['quantidade'];
    $parcelas = $_POST['parcelas'];
    $data_venda = date('Y-m-d');

    $valor_total = 0;

    if($servico_id != "NULL"){
        $servico = $conexao->query("SELECT valor FROM servicos WHERE id = $servico_id")->fetch_assoc();
        $valor_total += $servico['valor'];
    }

    if($produto_id != "NULL"){
        $produto = $conexao->query("SELECT valor FROM produtos WHERE id = $produto_id")->fetch_assoc();
        $valor_total += $produto['valor'] * $quantidade;

        $conexao->query("
            UPDATE produtos 
            SET quantidade = quantidade - $quantidade 
            WHERE id = $produto_id
        ");
    }

    $conexao->query("
        INSERT INTO vendas(cliente_id, servico_id, produto_id, quantidade, valor_total, data_venda)
        VALUES('$cliente_id', $servico_id, $produto_id, '$quantidade', '$valor_total', '$data_venda')
    ");

    $venda_id = $conexao->insert_id;
    $valor_parcela = $valor_total / $parcelas;

    for($i = 1; $i <= $parcelas; $i++){
        $vencimento = date('Y-m-d', strtotime("+$i month"));

        $conexao->query("
            INSERT INTO parcelas(venda_id, numero_parcela, valor, data_vencimento, status)
            VALUES('$venda_id', '$i', '$valor_parcela', '$vencimento', 'Pendente')
        ");
    }

    echo "<p>Venda cadastrada!</p>";
}

$clientes = $conexao->query("SELECT * FROM clientes");
$servicos = $conexao->query("SELECT * FROM servicos");
$produtos = $conexao->query("SELECT * FROM produtos");
?>

<h1>Registro de Vendas</h1>

<form method="POST">

Cliente:<br>
<select name="cliente_id" required>
    <option value="">Selecione</option>
    <?php while($cliente = $clientes->fetch_assoc()){ ?>
        <option value="<?php echo $cliente['id']; ?>">
            <?php echo $cliente['nome']; ?>
        </option>
    <?php } ?>
</select><br><br>

Serviço:<br>
<select name="servico_id">
    <option value="">Nenhum</option>
    <?php while($servico = $servicos->fetch_assoc()){ ?>
        <option value="<?php echo $servico['id']; ?>">
            <?php echo $servico['nome']; ?> - R$ <?php echo $servico['valor']; ?>
        </option>
    <?php } ?>
</select><br><br>

Produto:<br>
<select name="produto_id">
    <option value="">Nenhum</option>
    <?php while($produto = $produtos->fetch_assoc()){ ?>
        <option value="<?php echo $produto['id']; ?>">
            <?php echo $produto['nome']; ?> - R$ <?php echo $produto['valor']; ?>
        </option>
    <?php } ?>
</select><br><br>

Quantidade:<br>
<input type="number" name="quantidade" value="1" required><br><br>

Número de parcelas:<br>
<input type="number" name="parcelas" value="1" required><br><br>

<button type="submit" name="salvar">Registrar Venda</button>
</form>

<hr>

<h2>Vendas Registradas</h2>

<?php
$resultado = $conexao->query("
    SELECT v.id, c.nome AS cliente, v.valor_total, v.data_venda
    FROM vendas v
    JOIN clientes c ON c.id = v.cliente_id
    ORDER BY v.id DESC
");

while($venda = $resultado->fetch_assoc()){
    echo "Venda #".$venda['id']." - ";
    echo $venda['cliente']." - ";
    echo "R$ ".$venda['valor_total']." - ";
    echo $venda['data_venda']."<br>";
}
?>

<br><br>
<a href="dashboard.php">Voltar</a>