# Level Academy

## Descrição
Site institucional da Level Academy, com páginas informativas e um formulário de contato integrado a um banco de dados MySQL.

## Estrutura do Projeto
```
level-academy/
├── public/                # Arquivos públicos (imagens, favicon, etc.)
│   └── images/            # Imagens do site
│   └── assets/            # Arquivos CSS e JS
│       └── styles.css     # Estilos globais (CSS)
│       └── scripts.js     # Scripts JavaScript
├── src/                   # Diretório principal do código fonte
│   ├── components/        # Componentes HTML reutilizáveis
│   ├── pages/             # Páginas do site (HTML)
│   │   └── index.html     # Página inicial
│   │   └── about.html     # Página "Sobre"
│   │   └── services.html  # Página "Serviços"
│   │   └── contact.html   # Página "Contato"
│   └── php/               # Back-end em PHP
│       └── admin.php      # Painel do administrador
│       └── login.php      # Login do administrador
│       └── contact.php    # Processa o formulário de contato
│       └── config.php     # Configurações de conexão ao banco de dados
├── database.sql           # Script de criação da base de dados
└── README.md              # Este arquivo
```

## Configuração do Banco de Dados
1. Importe o arquivo `database.sql` para o seu servidor MySQL:
   ```
   mysql -u seu_usuario -p < database.sql
   ```
2. Edite o arquivo `src/php/config.php` com suas credenciais de banco de dados.

## Acesso ao Painel Administrativo
- URL: `http://seu-dominio.com/src/php/login.php`
- Usuário padrão: `admin`
- Senha padrão: `admin123`

## Recursos
- Design responsivo
- Formulário de contato funcional
- Painel administrativo com visualização de mensagens enviadas
- Banco de dados MySQL para armazenamento de informações 