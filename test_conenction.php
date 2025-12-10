<?php
// Teste temporário de conexão ao banco — remove este arquivo depois de usar

// Ajuste o caminho se o config.php estiver em outro diretório relativo
require_once __DIR__ . '/config.php';

// Tentar usar as constantes definidas no config.php (DB_HOST, DB_USER, DB_PASS, DB_NAME)
$mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
    echo "Falha na conexão com o banco de dados.<br>";
    echo "Erro: (" . $mysqli->connect_errno . ") " . htmlspecialchars($mysqli->connect_error);
    exit;
}

echo "Conectado com sucesso ao banco '" . htmlspecialchars(DB_NAME) . "' como usuário '" . htmlspecialchars(DB_USER) . "'.";
$mysqli->close();
?>
