CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(250) NOT NULL,
    cnpj VARCHAR(20) UNIQUE,
    telefone VARCHAR(20),
    email VARCHAR(250),
    endereco TEXT
);

CREATE TABLE itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(250) NOT NULL,
    descricao TEXT,
    quantidade INT NOT NULL DEFAULT 0
);

CREATE TABLE item_fornecedor (
    item_id INT NOT NULL,
    fornecedor_id INT NOT NULL,
    PRIMARY KEY (item_id, fornecedor_id),
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE CASCADE
);

CREATE TABLE entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantidade INT NOT NULL,
    data_entrada DATE NOT NULL,
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE
);

CREATE TABLE centros_de_custo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(191) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    role ENUM('admin', 'stock_manager', 'cost_center_employee') NOT NULL,
    centro_de_custo_id INT NULL,
    FOREIGN KEY (centro_de_custo_id) REFERENCES centros_de_custo(id)
);

CREATE TABLE saidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantidade INT NOT NULL,
    data_saida DATE NOT NULL,
    centro_de_custo_id INT,
    user_id INT,
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE,
    FOREIGN KEY (centro_de_custo_id) REFERENCES centros_de_custo(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE requisicoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantidade INT NOT NULL,
    centro_de_custo_id INT NOT NULL,
    user_id INT NOT NULL,
    data_requisicao DATE NOT NULL,
    status ENUM('Pendente', 'Aprovada', 'Rejeitada', 'Concluída') DEFAULT 'Pendente',
    data_aprovacao DATE NULL,
    aprovador_user_id INT NULL,
    observacoes TEXT NULL,
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE,
    FOREIGN KEY (centro_de_custo_id) REFERENCES centros_de_custo(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (aprovador_user_id) REFERENCES users(id)
);