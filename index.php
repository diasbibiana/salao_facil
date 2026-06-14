<?php
session_start();

if(isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Salão</title>
</head>
<body>

<h2>Login do Sistema</h2>

<form action="login.php" method="POST">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">
        Entrar
    </button>

</form>

</body>
</html>