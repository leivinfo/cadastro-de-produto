<?php
// ============================================
// PÁGINA DE CADASTRO DE USUÁRIOS
// ============================================

require_once 'config.php';

// Se usuário já está logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

$erro = '';
$sucesso = '';

// Processar formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirma_senha = trim($_POST['confirma_senha'] ?? '');
    
    // Validações
    if (empty($nome)) {
        $erro = 'Nome é obrigatório!';
    } elseif (empty($email)) {
        $erro = 'Email é obrigatório!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } elseif (empty($senha)) {
        $erro = 'Senha é obrigatória!';
    } elseif (strlen($senha) < 6) {
        $erro = 'Senha deve ter no mínimo 6 caracteres!';
    } elseif ($senha !== $confirma_senha) {
        $erro = 'As senhas não correspondem!';
    } else {
        // Verificar se email já existe
        $sql_check = "SELECT id FROM tb_usuarios WHERE email = ?";
        $stmt_check = $conexao->prepare($sql_check);
        
        if ($stmt_check === false) {
            die('Erro na preparação da query: ' . $conexao->error);
        }
        
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();
        
        if ($resultado_check->num_rows > 0) {
            $erro = 'Este email já está cadastrado!';
        } else {
            // Criptografar senha com bcrypt
            $senha_criptografada = password_hash($senha, PASSWORD_BCRYPT);
            
            // Inserir novo usuário
            $sql_insert = "INSERT INTO tb_usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt_insert = $conexao->prepare($sql_insert);
            
            if ($stmt_insert === false) {
                die('Erro na preparação da query: ' . $conexao->error);
            }
            
            $stmt_insert->bind_param("sss", $nome, $email, $senha_criptografada);
            
            if ($stmt_insert->execute()) {
                $sucesso = 'Cadastro realizado com sucesso! Faça login para continuar.';
                // Limpar formulário
                $nome = '';
                $email = '';
                $senha = '';
                $confirma_senha = '';
            } else {
                $erro = 'Erro ao cadastrar usuário: ' . $stmt_insert->error;
            }
            
            $stmt_insert->close();
        }
        
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Estoque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container-cadastro {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .container-cadastro h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }

        .btn-cadastro {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-cadastro:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-cadastro:active {
            transform: translateY(0);
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .link-login {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .link-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .link-login a:hover {
            text-decoration: underline;
        }

        .requisitos {
            background-color: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #333;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container-cadastro">
        <h1>Cadastro</h1>

        <?php if (!empty($erro)): ?>
            <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <div class="requisitos">
            <strong>Requisitos de Senha:</strong><br>
            • Mínimo 6 caracteres<br>
            • Deve ser confirmada
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="confirma_senha">Confirmar Senha:</label>
                <input type="password" id="confirma_senha" name="confirma_senha" required>
            </div>

            <button type="submit" class="btn-cadastro">Cadastrar</button>
        </form>

        <div class="link-login">
            Já possui conta? <a href="index_login.php">Faça login aqui</a>
        </div>
    </div>
</body>
</html>
