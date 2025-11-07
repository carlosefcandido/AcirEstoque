INSERT INTO fornecedores (nome, cnpj, telefone, email, endereco) VALUES
('Fornecedor A', '11.111.111/0001-11', '(11) 1111-1111', 'contato@fornecedora.com', 'Rua A, 123'),
('Fornecedor B', '22.222.222/0001-22', '(22) 2222-2222', 'contato@fornecedorb.com', 'Rua B, 456');

INSERT INTO itens (nome, descricao, quantidade) VALUES
('Caneta Azul', 'Caneta esferográfica de tinta azul', 100),
('Lápis HB', 'Lápis de escrever com grafite HB', 250),
('Resma', 'Resma de 500 folhas de papel A4', 50);

INSERT INTO centros_de_custo (nome) VALUES
('Contas a Pagar'),
('Contas a Receber'),
('Pasta');

-- Insert initial users
INSERT INTO users (username, password, role, centro_de_custo_id) VALUES
('admin', '$2y$10$w3LHZ6T.8tHFiftN44UOW.z8XdWJLxgdihhkgG3Qwt03r36MMUNjO', 'admin', NULL), -- Replace with actual hashed password
('stock_manager', '$2y$10$w3LHZ6T.8tHFiftN44UOW.z8XdWJLxgdihhkgG3Qwt03r36MMUNjO', 'estoquista', NULL), -- Replace with actual hashed password
('cost_center_user', '$2y$10$w3LHZ6T.8tHFiftN44UOW.z8XdWJLxgdihhkgG3Qwt03r36MMUNjO', 'funcionario', (SELECT id FROM centros_de_custo WHERE nome = 'Contas a Pagar')); -- Replace with actual hashed password