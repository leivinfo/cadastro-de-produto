<?php
// ============================================
// ARQUIVO DE CONFIGURAÇÃO DO BANCO DE DADOS
// ============================================

// Configurações para hospedagem local
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'seu_banco_dados');

// CONFIGURAÇÕES PARA HOSPEDAGEM ONLINE
// Descomente abaixo e preencha com os dados do seu servidor
define('DB_HOST', 'localhost'); // Endereço do servidor MySQL
define('DB_USER', 'davidtah_produtos_user'); // Usuário do banco
define('DB_PASS', 'produtos123'); // Senha do banco
define('DB_NAME', 'davidtah_DBPRODUTOS'); // Nome do banco

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

// Configurações de segurança
error_reporting(E_ALL);
ini_set('display_errors', 0); // Não exibir erros em produção
ini_set('log_errors', 1);
?>








