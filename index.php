<?php
// ============================================
// PONTO DE ENTRADA PRINCIPAL
// ============================================

require_once 'verificar_sessao.php';

// Se usuário está logado, redireciona para dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Se não está logado, redireciona para login
header('Location: index_login.php');
exit();
?>
