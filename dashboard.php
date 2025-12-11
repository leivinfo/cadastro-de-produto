<?php
// ============================================
// DASHBOARD - PÁGINA PROTEGIDA
// ============================================
require_once 'config.php';

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index_login.php');
    exit();
}

// Buscar informações do usuário
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nome, email, data_criacao, data_ultima_login FROM tb_usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

// Formatar datas
$data_criacao = new DateTime($usuario['data_criacao']);
$data_ultima_login = $usuario['data_ultima_login'] ? new DateTime($usuario['data_ultima_login']) : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Estoque</title>
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
        .btn-acao {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745; /* Cor verde (pode mudar se quiser) */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .user-info p {
        margin: 0; /* Remove espaçamento padrão dos parágrafos */
        line-height: 1.2; /* Ajusta altura da linha para ficar mais compacto */
        }

/* Ajuste na classe existente .btn-acao */
.btn-acao {
    /* ... outras propriedades ... */
    /* margin-top: 20px;  <-- REMOVA ESTA LINHA */
}

.btn-acao:hover {
    background-color: #218838; /* Cor mais escura ao passar o mouse */
}
        .header h1 {
            font-size: 24px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-info p {
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-logout {
            background: white;
            color: #667eea;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }

        .info-item label {
            display: block;
            color: #666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-item span {
            display: block;
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #ddd;
            color: #333;
        }

        .btn-secondary:hover {
            background: #ccc;
            transform: translateY(-2px);
        }

        .welcome {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .welcome h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .welcome p {
            font-size: 16px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
        <div class="header">
        <h1>Sistema de Estoque</h1>
        <div class="header-right">
            <div class="user-info">
                <p>Olá, <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong></p>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
            </div>
            
        
            <a href="dashboard_produtos.php" class="btn-acao">Gerenciar Produtos</a>
            <form method="POST" action="logout.php" style="margin: 0;">
                <button type="submit" class="btn-logout">Sair</button>
            </form>
        </div>
            
        
    </div>

    <div class="container">
        <div class="welcome">
            <h3>Bem-vindo ao Dashboard!</h3>
            <p>Você está autenticado e pode acessar todas as funcionalidades do sistema.</p>
        </div>

        <div class="card">
            <h2>Informações da Conta</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nome</label>
                    <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <span><?php echo htmlspecialchars($usuario['email']); ?></span>
                </div>
                <div class="info-item">
                    <label>Data de Cadastro</label>
                    <span><?php echo $data_criacao->format('d/m/Y H:i:s'); ?></span>
                </div>
                <div class="info-item">
                    <label>Último Login</label>
                    <span><?php echo $data_ultima_login ? $data_ultima_login->format('d/m/Y H:i:s') : 'Primeiro login'; ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Ações</h2>
            <div class="actions">
                <a href="editar_perfil.php" class="btn btn-primary">Editar Perfil</a>
                <a href="alterar_senha.php" class="btn btn-primary">Alterar Senha</a>
                <form method="POST" action="logout.php" style="margin: 0;">
                    <button type="submit" class="btn btn-secondary">Sair da Conta</button>
                </form>
            </div>
        </div>

        <div class="card">
            <h2>Dicas de Segurança</h2>
            <ul style="color: #666; line-height: 1.8;">
                <li>✓ Utilize uma senha forte com números e caracteres especiais</li>
                <li>✓ Não compartilhe suas credenciais com outras pessoas</li>
                <li>✓ Altere sua senha regularmente (a cada 3 meses)</li>
                <li>✓ Faça logout ao usar computadores públicos</li>
                <li>✓ Mantenha seu navegador e sistema operacional atualizados</li>
            </ul>
        </div>
    </div>
</body>
</html>
