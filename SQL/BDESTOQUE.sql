-- SQL para o PostgreSQL

-- Tabela de Categorias
CREATE TABLE TBCATEGORIA (
    CODIGO VARCHAR(3) PRIMARY KEY,
    TITULO VARCHAR(30) NOT NULL
);

-- Tabela de Produtos
CREATE TABLE TPPRODUTO (
    SKU VARCHAR(10) PRIMARY KEY,
    NOME VARCHAR(50) NOT NULL,
    PRECO NUMERIC(10, 2) NOT NULL,
    QTDE INTEGER NOT NULL,
    CODCAT VARCHAR(3) REFERENCES TBCATEGORIA(CODIGO) -- Chave Estrangeira
);

-- Adicionar coluna para produtos perecíveis (facilita o Polimorfismo na leitura)
ALTER TABLE TPPRODUTO
ADD COLUMN DATA_VALIDADE DATE;

-- Inserir algumas categorias para testes
INSERT INTO TBCATEGORIA (CODIGO, TITULO) VALUES
('INF', 'Informática'),
('ALI', 'Alimentos'),
('LIM', 'Limpeza');