-- ============================================
-- TABELA DE USUÁRIOS PARA SISTEMA DE LOGIN
-- ============================================

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS tb_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_ultima_login TIMESTAMP NULL,
    ativo TINYINT(1) DEFAULT 1
);

-- Criar índice no email para melhor performance nas buscas
CREATE INDEX idx_email ON tb_usuarios(email);

-- Inserir um usuário de teste (senha: 123456)
INSERT INTO tb_usuarios (nome, email, senha) 
VALUES ('Usuário Teste', 'teste@exemplo.com', '$2y$10$YOixf1tHmVHQa4uKxZbh6OPST9/PgBkqquzi.Oy5G5LwcA8sQa8gW');

-- Mostrar a estrutura da tabela
DESC tb_usuarios;
