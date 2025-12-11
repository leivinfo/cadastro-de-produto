<?php
// ============================================
// VERIFICAR SESSÃO - RETORNA JSON
// ============================================
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'autenticado' => true,
        'usuario_id' => $_SESSION['usuario_id'],
        'usuario_nome' => $_SESSION['usuario_nome'],
        'usuario_email' => $_SESSION['usuario_email']
    ]);
} else {
    echo json_encode([
        'autenticado' => false
    ]);
}
?>
