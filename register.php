<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);

    // Verifica se a conexão está funcionando
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Verifica se a tabela `usuarios` existe e prepara a consulta
    $check_user = $conn->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ?");
    if (!$check_user) {
        die("Erro ao preparar consulta: " . $conn->error);
    }

    $check_user->bind_param("ss", $username, $email);
    $check_user->execute();
    $result = $check_user->get_result();

    if ($result->num_rows > 0) {
        $error = "Usuário ou e-mail já cadastrado!";
    } else {
        $query = $conn->prepare("INSERT INTO usuarios (username, email, password, tipo) VALUES (?, ?, ?, 'escritor')");
        if (!$query) {
            die("Erro ao preparar consulta de inserção: " . $conn->error);
        }
        $query->bind_param("sss", $username, $email, $password);

        if ($query->execute()) {
            $success = "Cadastro realizado com sucesso! Você pode agora fazer login.";
        } else {
            $error = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Criar Conta</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
        <div class="text-center mt-3">
            <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
