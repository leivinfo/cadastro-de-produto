<?php
// ============================================
// API REST EM PHP - GERENCIAMENTO DE PRODUTOS
// ============================================

require_once 'config.php';

// Função para verificar se usuário está autenticado
function verificarAutenticacao() {
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        echo json_encode(['mensagem' => 'Não autorizado. Faça login primeiro.']);
        exit();
    }
}

// Função para enviar resposta JSON
function respostaJSON($dados, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados);
    exit();
}

// Habilitar CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Definir Content-Type
header('Content-Type: application/json; charset=utf-8');

// Obter método HTTP e rota
$metodo = $_SERVER['REQUEST_METHOD'];
$rota = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$rota = str_replace('/api/', '', $rota);

// Obter corpo da requisição
$entrada = json_decode(file_get_contents("php://input"), true);

// ===== ROTAS DA API =====

// GET /api/produtos - Listar todos os produtos
if ($metodo === 'GET' && preg_match('/^produtos\/?$/', $rota)) {
    verificarAutenticacao();
    
    try {
        $sql = "SELECT * FROM TPPRODUTO ORDER BY SKU";
        $resultado = $conexao->query($sql);
        
        $produtos = [];
        while ($linha = $resultado->fetch_assoc()) {
            $sku = $linha['SKU'];
            $nome = $linha['NOME'];
            $preco = number_format($linha['PRECO'], 2, '.', '');
            $qtde = $linha['QTDE'];
            $descricao = "Produto: $nome | SKU: $sku | Preço: R$ $preco";
            
            if (!empty($linha['DATA_VALIDADE'])) {
                $descricao .= " | Válido até: " . $linha['DATA_VALIDADE'];
            }
            
            $produtos[] = $descricao;
        }
        
        respostaJSON($produtos, 200);
    } catch (Exception $e) {
        respostaJSON(['mensagem' => 'Erro ao listar produtos: ' . $e->getMessage()], 500);
    }
}

// POST /api/produtos - Criar novo produto
else if ($metodo === 'POST' && preg_match('/^produtos\/?$/', $rota)) {
    verificarAutenticacao();
    
    try {
        $sku = $entrada['sku'] ?? '';
        $nome = $entrada['nome'] ?? '';
        $preco = $entrada['preco'] ?? '';
        $qtde = $entrada['qtde'] ?? '';
        $codCat = $entrada['codCat'] ?? '';
        $dataValidade = $entrada['dataValidade'] ?? null;
        
        // Validações básicas
        if (empty($sku) || empty($nome) || empty($preco) || empty($qtde) || empty($codCat)) {
            respostaJSON(['mensagem' => 'Campos obrigatórios não preenchidos'], 400);
        }
        
        if (strlen($sku) > 10) {
            respostaJSON(['mensagem' => 'SKU não pode ter mais de 10 caracteres'], 400);
        }
        
        // Preparar statement
        $sql = "INSERT INTO TPPRODUTO (SKU, NOME, PRECO, QTDE, CODCAT, DATA_VALIDADE) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar query: " . $conexao->error);
        }
        
        // Bind dos parâmetros
        $stmt->bind_param(
            "ssddss",
            $sku,
            $nome,
            $preco,
            $qtde,
            $codCat,
            $dataValidade
        );
        
        // Executar
        if ($stmt->execute()) {
            respostaJSON([
                'mensagem' => 'Produto criado com sucesso.',
                'produto' => [
                    'sku' => $sku,
                    'nome' => $nome,
                    'preco' => $preco,
                    'qtde' => $qtde,
                    'codCat' => $codCat,
                    'dataValidade' => $dataValidade
                ]
            ], 201);
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $erro = $e->getMessage();
        // Verificar se é violação de chave primária
        if (strpos($erro, 'Duplicate') !== false) {
            respostaJSON(['mensagem' => 'SKU já existe'], 409);
        }
        respostaJSON(['mensagem' => 'Erro ao criar produto: ' . $erro], 500);
    }
}

// PUT /api/produtos/:sku - Atualizar produto
else if ($metodo === 'PUT' && preg_match('/^produtos\/(.+)$/', $rota, $matches)) {
    verificarAutenticacao();
    
    try {
        $sku = $matches[1];
        
        // Construir SQL dinamicamente
        $campos = [];
        $valores = [];
        $tipos = "";
        
        if (isset($entrada['nome']) && !empty($entrada['nome'])) {
            $campos[] = "NOME = ?";
            $valores[] = $entrada['nome'];
            $tipos .= "s";
        }
        if (isset($entrada['preco']) && $entrada['preco'] !== '') {
            $campos[] = "PRECO = ?";
            $valores[] = $entrada['preco'];
            $tipos .= "d";
        }
        if (isset($entrada['qtde']) && $entrada['qtde'] !== '') {
            $campos[] = "QTDE = ?";
            $valores[] = $entrada['qtde'];
            $tipos .= "d";
        }
        if (isset($entrada['codCat']) && !empty($entrada['codCat'])) {
            $campos[] = "CODCAT = ?";
            $valores[] = $entrada['codCat'];
            $tipos .= "s";
        }
        if (isset($entrada['dataValidade'])) {
            $campos[] = "DATA_VALIDADE = ?";
            $valores[] = $entrada['dataValidade'] ?: null;
            $tipos .= "s";
        }
        
        if (empty($campos)) {
            respostaJSON(['mensagem' => 'Nenhum campo para atualizar'], 400);
        }
        
        // Adicionar SKU ao final
        $valores[] = $sku;
        $tipos .= "s";
        
        // Construir SQL
        $sql = "UPDATE TPPRODUTO SET " . implode(", ", $campos) . " WHERE SKU = ?";
        
        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar query: " . $conexao->error);
        }
        
        // Bind dinâmico
        $stmt->bind_param($tipos, ...$valores);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                respostaJSON([
                    'mensagem' => 'Produto atualizado com sucesso.',
                    'detalhe' => 'Atualizações aplicadas'
                ], 200);
            } else {
                respostaJSON(['mensagem' => "Produto SKU $sku não encontrado"], 404);
            }
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        respostaJSON(['mensagem' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
    }
}

// DELETE /api/produtos/:sku - Deletar produto
else if ($metodo === 'DELETE' && preg_match('/^produtos\/(.+)$/', $rota, $matches)) {
    verificarAutenticacao();
    
    try {
        $sku = $matches[1];
        
        $sql = "DELETE FROM TPPRODUTO WHERE SKU = ?";
        $stmt = $conexao->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar query: " . $conexao->error);
        }
        
        $stmt->bind_param("s", $sku);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                respostaJSON(['mensagem' => "Produto SKU $sku removido com sucesso"], 200);
            } else {
                respostaJSON(['mensagem' => "Produto SKU $sku não encontrado"], 404);
            }
        } else {
            throw new Exception($stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        respostaJSON(['mensagem' => 'Erro ao deletar: ' . $e->getMessage()], 500);
    }
}

// Rota não encontrada
else {
    respostaJSON(['mensagem' => 'Rota não encontrada'], 404);
}

$conexao->close();
?>
