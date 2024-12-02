<?php
session_start();
include 'db_connect.php';

// Verifica se o usuário logado é um administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: adminlogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica se as senhas coincidem
    if ($password !== $confirm_password) {
        $error = "As senhas não coincidem.";
    } else {
        // Verifica se o nome de usuário já existe
        $query = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $error = "O nome de usuário já está em uso.";
        } else {
            // Insere o novo administrador no banco de dados
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $tipo = "admin";

            $insert = $conn->prepare("INSERT INTO usuarios (username, password, tipo) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed_password, $tipo);

            if ($insert->execute()) {
                $success = "Administrador cadastrado com sucesso!";
            } else {
                $error = "Erro ao cadastrar administrador.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastrar Novo Administrador</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirme a Senha</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="admin.php">Voltar ao Painel Administrativo</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
