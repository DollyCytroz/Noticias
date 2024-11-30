<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $query = $conn->prepare("SELECT * FROM usuarios WHERE username = ? AND password = ? AND tipo = 'admin'");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['tipo'] = 'escritor';
        header("Location: escritor.php");
        exit;
    } else {
        $error = "Credenciais inválidas!";
    }
}
?>
<!-- Código HTML para o formulário, igual ao anterior -->
