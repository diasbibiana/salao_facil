<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_GET['pagar'])){
    $id = $_GET['pagar'];

    $conexao->query("
        UPDATE parcelas
        SET status = 'Paga'
        WHERE id = $id
    ");

    echo "<p>Parcela marcada como paga!</p>";
}
?>

<h1>Controle de Parcelas</h1>

<?php
$resultado = $conexao->query("
    SELECT p.id, p.venda_id, p.numero_parcela, p.valor, p.data_vencimento, p.status, c.nome AS cliente
    FROM parcelas p
    JOIN vendas v ON v.id = p.venda_id
    JOIN clientes c ON c.id = v.cliente_id
    ORDER BY p.data_vencimento
");

while($parcela = $resultado->fetch_assoc()){
    echo "Cliente: ".$parcela['cliente']." - ";
    echo "Venda #".$parcela['venda_id']." - ";
    echo "Parcela ".$parcela['numero_parcela']." - ";
    echo "R$ ".$parcela['valor']." - ";
    echo "Vencimento: ".$parcela['data_vencimento']." - ";
    echo "Status: ".$parcela['status'];

    if($parcela['status'] == 'Pendente'){
        echo " - <a href='parcelas.php?pagar=".$parcela['id']."'>Marcar como paga</a>";
    }

    echo "<br>";
}
?>

<br><br>
<a href="dashboard.php">Voltar</a>