<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- CREDENCIAIS (preferir definir via variáveis de ambiente em produção) ----------
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'davidtah_produtos_user');
define('DB_PASS', getenv('DB_PASS') ?: 'senhateste123');
define('DB_NAME', getenv('DB_NAME') ?: 'davidtah_DBPRODUTOS');

// ---------- Conexão com MySQL (modo exceção) ----------
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conexao->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Loga o erro para o arquivo de log do PHP, sem expor credenciais ao usuário
    error_log('DB connection error: ' . $e->getMessage());
    // Mensagem genérica ao cliente
    if (ini_get('display_errors')) {
        // Apenas durante debug
        die('Erro ao conectar com o banco: ' . $e->getMessage());
    } else {
        die('Erro ao conectar com o banco de dados. Entre em contato com o suporte.');
    }
}

// ---------- Configurações de erro (produção) ----------
error_reporting(E_ALL);
ini_set('display_errors', 0);   // NÃO exibir erros em produção
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log'); // opcional: log local

// NÃO feche a tag PHP para evitar envio acidental de espaços/linhas após o arquivo