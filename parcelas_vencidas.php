<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Parcelas Vencidas</title>
</head>
<body>

<h1>Relatório de Parcelas Vencidas</h1>

<?php

$resultado = $conexao->query("
SELECT p.*,
       c.nome AS cliente
FROM parcelas p
INNER JOIN vendas v
    ON v.id = p.venda_id
INNER JOIN clientes c
    ON c.id = v.cliente_id
WHERE p.data_vencimento < CURDATE()
AND p.status = 'Pendente'
ORDER BY p.data_vencimento
");

if($resultado->num_rows == 0){

    echo "<p>Nenhuma parcela vencida encontrada.</p>";

}else{

    while($parcela = $resultado->fetch_assoc()){

        echo "Cliente: ".$parcela['cliente']." - ";
        echo "Venda #".$parcela['venda_id']." - ";
        echo "Parcela ".$parcela['numero_parcela']." - ";
        echo "R$ ".$parcela['valor']." - ";
        echo "Vencimento: ".$parcela['data_vencimento']."<br><br>";
    }
}
?>

<br>

<a href="dashboard.php">Voltar</a>

</body>
</html>