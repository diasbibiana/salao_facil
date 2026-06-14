<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

$clientes = $conexao->query("SELECT * FROM clientes ORDER BY nome");

$cliente_id = $_GET['cliente_id'] ?? '';
?>

<?php include("estilo.php"); ?>

<?php include("menu.php"); ?>

<div class="container">

<h1>Histórico do Cliente</h1>

<form method="GET" class="mb-4">
    <label>Selecione o cliente:</label>
    <select name="cliente_id" class="form-control" required>
        <option value="">Selecione</option>

        <?php while($cliente = $clientes->fetch_assoc()){ ?>
            <option value="<?php echo $cliente['id']; ?>" <?php if($cliente_id == $cliente['id']) echo "selected"; ?>>
                <?php echo $cliente['nome']; ?>
            </option>
        <?php } ?>
    </select>

    <br>

    <button class="btn btn-primary" type="submit">Buscar Histórico</button>
</form>

<?php if($cliente_id != ''){ ?>

<hr>

<h2>Agendamentos</h2>

<?php
$agendamentos = $conexao->query("
    SELECT a.data_agendamento, a.hora_agendamento, s.nome AS servico
    FROM agendamentos a
    JOIN servicos s ON s.id = a.servico_id
    WHERE a.cliente_id = $cliente_id
    ORDER BY a.data_agendamento DESC, a.hora_agendamento DESC
");

if($agendamentos->num_rows == 0){
    echo "<p>Nenhum agendamento encontrado.</p>";
}

while($agenda = $agendamentos->fetch_assoc()){
    echo "<p>".$agenda['data_agendamento']." ".$agenda['hora_agendamento']." - ".$agenda['servico']."</p>";
}
?>

<h2>Vendas</h2>

<?php
$vendas = $conexao->query("
    SELECT id, valor_total, data_venda
    FROM vendas
    WHERE cliente_id = $cliente_id
    ORDER BY data_venda DESC
");

if($vendas->num_rows == 0){
    echo "<p>Nenhuma venda encontrada.</p>";
}

while($venda = $vendas->fetch_assoc()){
    echo "<p>Venda #".$venda['id']." - R$ ".$venda['valor_total']." - ".$venda['data_venda']."</p>";
}
?>

<h2>Parcelas</h2>

<?php
$parcelas = $conexao->query("
    SELECT p.numero_parcela, p.valor, p.data_vencimento, p.status, p.venda_id
    FROM parcelas p
    JOIN vendas v ON v.id = p.venda_id
    WHERE v.cliente_id = $cliente_id
    ORDER BY p.data_vencimento DESC
");

if($parcelas->num_rows == 0){
    echo "<p>Nenhuma parcela encontrada.</p>";
}

while($parcela = $parcelas->fetch_assoc()){
    echo "<p>Venda #".$parcela['venda_id']." - ";
    echo "Parcela ".$parcela['numero_parcela']." - ";
    echo "R$ ".$parcela['valor']." - ";
    echo "Vencimento: ".$parcela['data_vencimento']." - ";
    echo "Status: ".$parcela['status']."</p>";
}
?>

<?php } ?>

<br>
<a class="btn btn-secondary" href="dashboard.php">Voltar</a>

</div>