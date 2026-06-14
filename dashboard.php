<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema Salão</title>
    <?php include("estilo.php"); ?>
</head>

<body>

<?php include("menu.php"); ?>

<div class="container">

    <div class="card">
        <div class="card-body">
            <h1>Sistema de Salão de Beleza</h1>
            <p>Bem-vindo, <?php echo $_SESSION['admin']; ?></p>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <a class="btn btn-primary w-100" href="clientes.php">Clientes</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-primary w-100" href="servicos.php">Serviços</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-primary w-100" href="produtos.php">Produtos</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-success w-100" href="agendamentos.php">Agendamentos</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-success w-100" href="vendas.php">Vendas</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-warning w-100" href="parcelas.php">Parcelas</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-danger w-100" href="parcelas_vencidas.php">Parcelas Vencidas</a>
                </div>

                <div class="col-md-4 mb-3">
                    <a class="btn btn-info w-100" href="historico_cliente.php">Histórico do Cliente</a>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>