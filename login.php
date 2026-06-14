<?php

session_start();

include("conexao.php");

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$resultado = $conexao->query("
    SELECT *
    FROM administradores
    WHERE email = '$email'
");

if ($resultado && $resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, trim($usuario['senha']))) {

        $_SESSION['admin'] = $usuario['nome'];

        header("Location: dashboard.php");
        exit;
    }
}

echo "Login inválido";

?>