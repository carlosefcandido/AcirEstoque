# AcirEstoque - Sistema de Controle de Almoxarifado

Sistema web para gerenciamento de estoque e almoxarifado desenvolvido em PHP.

## Funcionalidades

- Controle de usuários com 3 níveis de acesso:
  - Administrador
  - Gerente de Estoque 
  - Funcionário de Centro de Custo

- Gestão de Itens:
  - Cadastro e edição de itens
  - Controle de quantidades
  - Histórico de movimentações

- Entradas e Saídas:
  - Registro de entradas no estoque
  - Controle de saídas por centro de custo
  - Requisições de materiais

- Relatórios:
  - Níveis atuais de estoque
  - Resumo de requisições por status
  - Total de itens por centro de custo
  - Número de requisições por usuário

## Requisitos

- PHP 7.4+
- MySQL 5.7+
- Servidor Web (Apache/Nginx)

## Instalação

1. Clone o repositório:
```sh
git clone https://github.com/seu-usuario/acirestoque.git
```

2. Configure o banco de dados:
- Crie um banco de dados MySQL
- Importe o arquivo `database.sql`
- Configure as credenciais em `includes/db.php`

3. Crie o usuário admin inicial:
```sql
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$w3LHZ6T.8tHFiftN44UOW.z8XdWJLxgdihhkgG3Qwt03r36MMUNjO', 'admin');
```

4. Configure o servidor web para apontar para o diretório do projeto

## Estrutura do Projeto

```
├── assets/
│   └── css/
├── includes/
│   ├── db.php
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── login.php
│   ├── itens.php
│   ├── entradas.php
│   └── ...
├── database.sql
├── index.php
└── README.md
```

## Segurança

- Autenticação de usuários
- Controle de permissões por função
- Proteção contra SQL Injection
- Sanitização de dados
- Senhas armazenadas com hash

## Contribuindo

1. Fork o projeto
2. Crie sua Feature Branch (`git checkout -b feature/NovaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a Branch (`git push origin feature/NovaFeature`)
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Créditos

Desenvolvido como sistema de controle de almoxarifado para gerenciamento de estoque e requisições de materiais.