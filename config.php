<?php
// ============================================
// ARQUIVO DE CONFIGURAÇÃO DO BANCO DE DADOS
// ============================================

// HOST do MySQL (na maioria dos hosts compartilhados permanece 'localhost')
define('DB_HOST', 'localhost');

// Credenciais fornecidas
define('DB_USER', 'davitah_produtos_user');   // usuário do banco
define('DB_PASS', 'senhateste123');            // senha do usuário
define('DB_NAME', 'davitah_DBPRODUTOS');       // nome do banco

// Criar conexão com o banco de dados
$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro ao conectar com o banco de dados: " . $conexao->connect_error);
}

// Configurar charset para UTF-8
$conexao->set_charset("utf8mb4");

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações de segurança / logs
error_reporting(E_ALL);
ini_set('display_errors', 0); // Não exibir erros em produção
ini_set('log_errors', 1);
?>







