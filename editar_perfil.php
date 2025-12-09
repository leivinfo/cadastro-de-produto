<?php
// ============================================
// PÁGINA PARA EDITAR PERFIL
// ============================================

require_once 'config.php';

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index_login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

// Buscar dados do usuário
$sql = "SELECT nome, email FROM tb_usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

$nome = $usuario['nome'];
$email = $usuario['email'];

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_nome = trim($_POST['nome'] ?? '');
    $novo_email = trim($_POST['email'] ?? '');
    
    // Validações
    if (empty($novo_nome)) {
        $erro = 'Nome é obrigatório!';
    } elseif (empty($novo_email)) {
        $erro = 'Email é obrigatório!';
    } elseif (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } else {
        // Verificar se email já está em uso por outro usuário
        $sql_check = "SELECT id FROM tb_usuarios WHERE email = ? AND id != ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("si", $novo_email, $usuario_id);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();
        
        if ($resultado_check->num_rows > 0) {
            $erro = 'Este email já está em uso!';
        } else {
            // Atualizar dados do usuário
            $sql_update = "UPDATE tb_usuarios SET nome = ?, email = ? WHERE id = ?";
            $stmt_update = $conexao->prepare($sql_update);
            $stmt_update->bind_param("ssi", $novo_nome, $novo_email, $usuario_id);
            
            if ($stmt_update->execute()) {
                // Atualizar sessão
                $_SESSION['usuario_nome'] = $novo_nome;
                $_SESSION['usuario_email'] = $novo_email;
                
                $sucesso = 'Perfil atualizado com sucesso!';
                $nome = $novo_nome;
                $email = $novo_email;
            } else {
                $erro = 'Erro ao atualizar perfil!';
            }
            
            $stmt_update->close();
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
    <title>Editar Perfil - Sistema de Estoque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 24px;
        }

        .btn-voltar {
            background: white;
            color: #667eea;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-voltar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
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

        .btn-salvar {
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

        .btn-salvar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Editar Perfil</h1>
        <a href="dashboard.php" class="btn-voltar">← Voltar</a>
    </div>

    <div class="container">
        <div class="card">
            <?php if (!empty($erro)): ?>
                <div class="erro"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <?php if (!empty($sucesso)): ?>
                <div class="sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <button type="submit" class="btn-salvar">Salvar Alterações</button>
            </form>
        </div>
    </div>
</body>
</html>
