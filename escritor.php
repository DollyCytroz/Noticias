<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['tipo'] !== 'escritor') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = htmlspecialchars(strip_tags($_POST['titulo']));
    $autor = $_SESSION['username'];
    $texto = htmlspecialchars(strip_tags($_POST['texto']));
    $imagem = $_FILES['imagem']['name'];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($imagem, PATHINFO_EXTENSION));
    $novo_nome = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $novo_nome;

    $check = getimagesize($_FILES['imagem']['tmp_name']);
    if ($check === false) {
        $error = "O arquivo enviado não é uma imagem.";
    } elseif ($_FILES['imagem']['size'] > 2000000) {
        $error = "O arquivo enviado é muito grande. Máximo permitido: 2MB.";
    } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $error = "Somente arquivos JPG, JPEG, PNG, GIF e WEBP são permitidos.";
    } else {
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
            $query = $conn->prepare("INSERT INTO noticias (titulo, autor, texto, imagem) VALUES (?, ?, ?, ?)");
            $query->bind_param("ssss", $titulo, $autor, $texto, $novo_nome);
            if ($query->execute()) {
                $success = "Notícia enviada para aprovação!";
            } else {
                $error = "Erro ao salvar notícia no banco de dados.";
            }
        } else {
            $error = "Erro ao mover o arquivo para o diretório de uploads.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Criar Notícia</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="texto" class="form-label">Texto</label>
                <textarea name="texto" id="texto" class="form-control" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem</label>
                <input type="file" name="imagem" id="imagem" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
