<?php
// ============================================
// PÁGINA DE LOGIN
// ============================================

require_once 'config.php';

// Se usuário já está logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

$erro = '';
$sucesso = '';

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = 'Email e senha são obrigatórios!';
    } else {
        // Buscar usuário no banco de dados
        $sql = "SELECT id, nome, email, senha FROM tb_usuarios WHERE email = ? AND ativo = 1";
        $stmt = $conexao->prepare($sql);
        
        if ($stmt === false) {
            die('Erro na preparação da query: ' . $conexao->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar senha usando password_verify (bcrypt)
            if (password_verify($senha, $usuario['senha'])) {
                // Senha correta - criar sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                // Atualizar data do último login
                $sql_update = "UPDATE tb_usuarios SET data_ultima_login = NOW() WHERE id = ?";
                $stmt_update = $conexao->prepare($sql_update);
                $stmt_update->bind_param("i", $usuario['id']);
                $stmt_update->execute();
                $stmt_update->close();
                
                // Redirecionar para dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $erro = 'Email ou senha incorretos!';
            }
        } else {
            $erro = 'Email ou senha incorretos!';
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
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

        .container-login {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .container-login h1 {
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
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

        .link-cadastro {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .link-cadastro a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .link-cadastro a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-login">
        <h1>Login</h1>

        <?php if (!empty($erro)): ?>
            <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="link-cadastro">
            Não possui conta? <a href="cadastro.php">Cadastre-se aqui</a>
        </div>
    </div>
</body>
</html>
