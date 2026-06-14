<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_POST['salvar'])){

    $cliente_id = $_POST['cliente_id'];
    $servico_id = $_POST['servico_id'];
    $data = $_POST['data_agendamento'];
    $hora = $_POST['hora_agendamento'];

    $verifica = $conexao->query("
        SELECT * FROM agendamentos 
        WHERE data_agendamento = '$data' 
        AND hora_agendamento = '$hora'
    ");

    if($verifica->num_rows > 0){
        echo "<p>Horário indisponível!</p>";
    } else {
        $conexao->query("
            INSERT INTO agendamentos(cliente_id, servico_id, data_agendamento, hora_agendamento)
            VALUES('$cliente_id','$servico_id','$data','$hora')
        ");

        echo "<p>Agendamento cadastrado!</p>";
    }
}

$clientes = $conexao->query("SELECT * FROM clientes");
$servicos = $conexao->query("SELECT * FROM servicos");
?>

<h1>Agendamento de Serviços</h1>

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
<select name="servico_id" required>
    <option value="">Selecione</option>
    <?php while($servico = $servicos->fetch_assoc()){ ?>
        <option value="<?php echo $servico['id']; ?>">
            <?php echo $servico['nome']; ?>
        </option>
    <?php } ?>
</select><br><br>

Data:<br>
<input type="date" name="data_agendamento" required><br><br>

Hora:<br>
<input type="time" name="hora_agendamento" required><br><br>

<button type="submit" name="salvar">Agendar</button>

</form>

<hr>

<h2>Agendamentos Cadastrados</h2>

<?php
$resultado = $conexao->query("
    SELECT a.id, c.nome AS cliente, s.nome AS servico, a.data_agendamento, a.hora_agendamento
    FROM agendamentos a
    JOIN clientes c ON c.id = a.cliente_id
    JOIN servicos s ON s.id = a.servico_id
    ORDER BY a.data_agendamento, a.hora_agendamento
");

while($agenda = $resultado->fetch_assoc()){
    echo $agenda['cliente']." - ";
    echo $agenda['servico']." - ";
    echo $agenda['data_agendamento']." - ";
    echo $agenda['hora_agendamento']."<br>";
}
?>

<br><br>
<a href="dashboard.php">Voltar</a>