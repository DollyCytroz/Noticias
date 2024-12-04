<?php

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; // Campo email do formulário
    $password = $_POST['password']; 

    // Corrigir o nome da coluna para "password"
    $query = $conn->prepare("INSERT INTO usuarios (username, email, password, tipo) VALUES (?, ?, ?, 'admin')");

    if (!$query) {
        die("Erro na consulta: " . $conn->error); // Exibir o erro do banco de dados
    }

    $query->bind_param("sss", $username, $email, $password);

    if ($query->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Erro ao registrar o administrador. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="adminregister.css" rel="stylesheet"> 
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f4f4f4; flex-direction: column;">

    <!-- Formulário de Registro -->
    <div class="container bg-white p-4 rounded shadow-sm mb-3" style="max-width: 500px;">
        <h1 class="text-center mb-4">Registrar Administrador</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Registrar</button>
        </form>

        <div class="mt-3 text-center">
                <a href="login.php"> Já é um admin? Entre aqui!!!.</a>
            </div>
    </div>

    <!-- Botão "Voltar para Página Inicial" -->
    <a href="index.php" class="btn btn-secondary w-100 text-center" style="max-width: 500px;">Voltar para Página Inicial</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
