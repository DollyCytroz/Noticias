<?php
session_start();
include 'db_connect.php'; // Banco

// Verificar se o usuário está logado
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Consulta para pegar as notícias
$query = "SELECT * FROM noticias WHERE aprovado = 1"; // Ordenando pela data de publicação
$result = $conn->query($query);

// Verifica se a consulta foi bem-sucedida
$noticias = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $noticias[] = $row;
        }
    } else {
        $noticias = null; // Sem notícias disponíveis
    }
} else {
    die("Erro na consulta: " . $conn->error); // Exibe o erro de SQL
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .noticias-container {
            background-color: #6c757d;
            padding: 30px;
            border-radius: 15px; 
        }
    </style>
</head>
<body class="fundo">
    <!-- Barra de navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <center> <a class="navbar-brand"> RAP NEWS </a> </center>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Verifica se o usuário NÃO está logado -->
                <?php if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"> Login </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['tipo'] == 'escritor'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="escritor.php">Criar Notícia</a>
                    </li>
                <?php endif; ?>

                li class = "nav-item"> 
                    <a class= "nav-link" href = "adminregister.php"> Ajude-nos </a> 
                </li>

                <?php if (isset($_SESSION['loggedin'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"> Logout </a>
                    </li>
                <?php endif; ?>

                <
            </ul>
        </div>
    </nav>

    <!-- Bloco Cinza mais intenso para as Notícias -->
    <div class="container mt-5 noticias-container">
        <h1 class="text-center text-light mb-4">Notícias Recentes</h1>

        <?php if ($noticias !== null): ?>
            <div class="row">
                <?php foreach ($noticias as $noticia): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-dark text-white shadow-sm rounded">
                            <img src="uploads/<?php echo htmlspecialchars($noticia['imagem']); ?>" class="card-img-top" alt="Imagem da notícia">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                                <p class="card-text"><?php echo substr($noticia['texto'], 0, 150) . '...'; ?></p>
                                <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-primary">Ler mais</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-light">Ainda não há notícias publicadas.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
