<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location:index.php");
    exit;
}

include("conexao.php");

if(isset($_GET['excluir'])){
    $id = $_GET['excluir'];

    $verifica = $conexao->query("
        SELECT id FROM agendamentos
        WHERE cliente_id = $id
        LIMIT 1
    ");

    if($verifica->num_rows > 0){
        echo "<p>Não é possível excluir este cliente, pois ele possui agendamentos cadastrados.</p>";
    } else {
        $conexao->query("DELETE FROM clientes WHERE id = $id");

        header("Location: clientes.php");
        exit;
    }
}

$cliente_editar = null;

if(isset($_GET['editar'])){
    $id = $_GET['editar'];

    $resultado_editar = $conexao->query("SELECT * FROM clientes WHERE id = $id");
    $cliente_editar = $resultado_editar->fetch_assoc();
}

if(isset($_POST['salvar'])){

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO clientes(nome,telefone,email)
            VALUES('$nome','$telefone','$email')";

    $conexao->query($sql);

    echo "<p>Cliente cadastrado!</p>";
}

if(isset($_POST['atualizar'])){

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $conexao->query("
        UPDATE clientes
        SET nome = '$nome',
            telefone = '$telefone',
            email = '$email'
        WHERE id = $id
    ");

    header("Location: clientes.php");
    exit;
}
?>

<h1>Cadastro de Clientes</h1>

<form method="POST">

<?php if($cliente_editar){ ?>
    <input type="hidden" name="id" value="<?php echo $cliente_editar['id']; ?>">
<?php } ?>

Nome:<br>
<input type="text" name="nome" required value="<?php echo $cliente_editar['nome'] ?? ''; ?>"><br><br>

Telefone:<br>
<input type="text" name="telefone" value="<?php echo $cliente_editar['telefone'] ?? ''; ?>"><br><br>

Email:<br>
<input type="email" name="email" value="<?php echo $cliente_editar['email'] ?? ''; ?>"><br><br>

<?php if($cliente_editar){ ?>
    <button type="submit" name="atualizar">Atualizar</button>
    <a href="clientes.php">Cancelar</a>
<?php } else { ?>
    <button type="submit" name="salvar">Salvar</button>
<?php } ?>

</form>

<hr>

<h2>Clientes Cadastrados</h2>

<?php
$resultado = $conexao->query("SELECT * FROM clientes");

while($cliente = $resultado->fetch_assoc()){

    echo $cliente['nome']." - ";
    echo $cliente['telefone']." - ";
    echo $cliente['email']." ";

    echo "<a href='clientes.php?editar=".$cliente['id']."'>Editar</a> ";
    echo "<a href='clientes.php?excluir=".$cliente['id']."' onclick=\"return confirm('Deseja excluir este cliente?')\">Excluir</a>";

    echo "<br>";
}
?>

<br><br>

<a href="dashboard.php">Voltar</a>