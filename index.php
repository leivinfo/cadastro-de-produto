<?php
require_once __DIR__ . '/config.php';

// LINHA REMOVIDA: require_once 'verificar_sessao.php'; <--- ISSO ESTAVA BLOQUEANDO O REDIRECIONAMENTO

// Se usuário está logado, redireciona para dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Se não está logado, redireciona para login
header('Location: index_login.php');
exit();
?>