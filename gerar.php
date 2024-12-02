<?php
// Defina a senha para o primeiro administrador
$senha = 'SENHA_DO_ADMIN';  // Aqui você coloca a senha que quer para o primeiro administrador, por exemplo: 'admin123'

// Gere o hash da senha
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Exiba o hash gerado para você copiar
echo $hash;
?>
