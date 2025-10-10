CREATE DATABASE IF NOT EXISTS serenity_perfumaria;
USE serenity_perfumaria;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'gerente', 'vendedor') DEFAULT 'vendedor',
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE fornecedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) UNIQUE NOT NULL,
    endereco TEXT,
    telefone VARCHAR(20),
    email VARCHAR(100),
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    descricao TEXT,
    quantidade_estoque INT DEFAULT 0,
    preco_unitario DECIMAL(10,2) DEFAULT 0.00,
    fornecedor_id INT,
    categoria ENUM('masculino', 'feminino', 'unissex', 'infantil'),
    volume VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
);

-- Inserir usuário admin (senha: 123456)
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@serenity.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserir alguns fornecedores de exemplo
INSERT INTO fornecedores (nome, cnpj, endereco, telefone, email) VALUES 
('Essências Luxo Ltda', '12345678000195', 'Rua das Flores, 123 - São Paulo/SP', '(11) 9999-8888', 'contato@essenciasluxo.com.br'),
('Aromas Finos S.A.', '98765432000101', 'Av. Principal, 456 - Rio de Janeiro/RJ', '(21) 8888-7777', 'vendas@aromasfinos.com.br');

-- Inserir alguns produtos de exemplo
INSERT INTO produtos (nome, codigo, descricao, quantidade_estoque, preco_unitario, fornecedor_id, categoria, volume) VALUES 
('Eau de Serenity', 'SER-001', 'Perfume floral amadeirado com notas cítricas', 25, 199.90, 1, 'feminino', '100ml'),
('Noite Estrelada', 'SER-002', 'Fragrância masculina intensa e sofisticada', 18, 249.90, 2, 'masculino', '100ml');