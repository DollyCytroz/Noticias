<?php
session_start();
include 'db_connect.php'; // Inclua sua conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['senha'];

    // Consulta para verificar o tipo do usuário e a senha (agora usando 'password')
    $query = $conn->prepare("SELECT id, password, tipo FROM usuarios WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        $query->bind_result($id, $stored_password, $tipo);
        $query->fetch();

        // Verifica se a senha digitada é igual à armazenada
        if ($senha === $stored_password) {
            // Armazenar dados na sessão
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['tipo'] = $tipo;

            // Redirecionar com base no tipo
            if ($tipo == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100" style="background-color: #f8f9fa;">
    <div class="container" style="max-width: 400px;">
        <div class="bg-white p-4 rounded shadow">
            <h1 class="text-center mb-4">Login</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php">Ainda não tem uma conta? Registre-se aqui.</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


