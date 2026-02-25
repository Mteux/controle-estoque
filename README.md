# 📦 Sistema de Controle de Estoque

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

> Sistema completo de controle de estoque desenvolvido em PHP com arquitetura MVC, permitindo gerenciar produtos, gerar relatórios e controlar o estoque de forma eficiente.

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação e Configuração](#-instalação-e-configuração)
- [Como Usar](#-como-usar)
- [Capturas de Tela](#-capturas-de-tela)
- [Funcionalidades Futuras](#-funcionalidades-futuras)
- [Contribuição](#-contribuição)
- [Licença](#-licença)
- [Contato](#-contato)

---

## 🎯 Sobre o Projeto

Este sistema de controle de estoque foi desenvolvido para facilitar o gerenciamento de produtos, oferecendo uma interface intuitiva e relatórios completos. Ideal para pequenas e médias empresas que precisam controlar seu inventário de forma prática e eficiente.

**🎨 Design:** Interface moderna e responsiva com Bootstrap  
**📊 Relatórios:** Exportação em CSV, Excel e PDF  
**🔒 Segurança:** Autenticação de usuários e gerenciamento de sessões

---

## ✨ Funcionalidades

### ✅ **CRUD Completo**
- **C**reate: Adicionar novos produtos
- **R**ead: Visualizar todos os produtos em tabela
- **U**pdate: Editar informações dos produtos
- **D**elete: Excluir produtos com confirmação

### 🔍 **Filtros e Buscas**
- Filtrar produtos por nome, categoria, valor e quantidade
- Limpar filtros rapidamente
- Busca avançada (em desenvolvimento)

### 📊 **Relatórios**
- Exportar lista de produtos em **CSV**
- Exportar relatórios completos em **Excel (XLS)**
- Gerar relatórios em **PDF** com formatação profissional
- Estatísticas detalhadas do estoque

### 👥 **Autenticação**
- Cadastro de novos usuários
- Login com sessão segura
- Proteção de rotas (acesso apenas para usuários logados)
- Logout automático

### 🎨 **Interface**
- Design responsivo (funciona em celular, tablet e desktop)
- Cards de estatísticas no dashboard
- Modal para escolha de formato de exportação
- Feedback visual para ações do usuário

---

## 🛠️ Tecnologias Utilizadas

### **Backend**
- ![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white) - Linguagem principal
- ![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white) - Banco de dados
- ![Composer](https://img.shields.io/badge/Composer-2.0-885630?style=flat-square&logo=composer&logoColor=white) - Gerenciador de dependências
- **Arquitetura MVC** - Organização do código
- **PDO** - Conexão segura com o banco

### **Frontend**
- ![Bootstrap](https://img.shields.io/badge/Bootstrap-4-7952B3?style=flat-square&logo=bootstrap&logoColor=white) - Framework CSS
- ![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black) - Interatividade
- ![FontAwesome](https://img.shields.io/badge/FontAwesome-5-339AF0?style=flat-square&logo=font-awesome&logoColor=white) - Ícones
- **HTML5** e **CSS3** - Estrutura e estilização

### **Bibliotecas e Pacotes**
- **DOMPDF** - Geração de PDFs
- **Mailjet** - Envio de emails (feedback)
- **jQuery** - Manipulação do DOM

---

## 📁 Estrutura do Projeto

```
📦 controle-estoque
├── 📂 App
│   ├── 📂 Controllers      # Controladores do sistema
│   │   ├── AppController.php
│   │   ├── AuthController.php
│   │   └── IndexController.php
│   ├── 📂 Models           # Modelos do banco de dados
│   │   ├── Produto.php
│   │   └── Usuario.php
│   ├── 📂 Views            # Arquivos de visualização (.phtml)
│   │   ├── 📂 app          # Views do AppController
│   │   │   ├── adicionar.phtml
│   │   │   ├── dashboard.phtml
│   │   │   ├── editar.phtml
│   │   │   ├── feedback.phtml
│   │   │   └── relatorios.phtml
│   │   └── 📂 index        # Views do IndexController
│   │       ├── index.phtml
│   │       └── inscreverse.phtml
│   └── Connection.php      # Configuração do banco
├── 📂 public               # Arquivos públicos
│   ├── 📂 css              # Estilos
│   │   └── style.css
│   ├── 📂 img              # Imagens
│   │   └── logo.png
│   ├── 📂 script           # Scripts JavaScript
│   │   └── script.js
│   └── index.php           # Ponto de entrada
├── 📂 vendor               # Dependências (Composer)
├── composer.json           # Configuração do Composer
└── README.md               # Documentação
```

---

## ⚙️ Pré-requisitos

Antes de começar, você precisa ter instalado em sua máquina:

- [![XAMPP](https://img.shields.io/badge/XAMPP-8.2-FB7A24?style=flat-square&logo=xampp&logoColor=white)](https://www.apachefriends.org/) - Servidor Apache + MySQL + PHP
- [![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/) - Linguagem
- [![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/) - Banco de dados
- [![Composer](https://img.shields.io/badge/Composer-2.0-885630?style=flat-square&logo=composer&logoColor=white)](https://getcomposer.org/) - Gerenciador de dependências
- Navegador moderno (Chrome, Firefox, Edge)

---

## 🚀 Instalação e Configuração

### 1. Clone o repositório
```bash
git clone https://github.com/mateusilva/controle-estoque.git
cd controle-estoque
```

### 2. Configure o XAMPP
- Mova a pasta do projeto para `C:\xampp\htdocs\controle-estoque`
- Inicie o Apache e MySQL no XAMPP Control Panel

### 3. Configure o banco de dados
- Acesse o phpMyAdmin: `http://localhost/phpmyadmin`
- Execute o script SQL para criar o banco e tabelas:
```sql
CREATE DATABASE controle_estoque;
USE controle_estoque;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    produto VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
```

### 4. Configure o arquivo de conexão
No arquivo `App/Connection.php`, ajuste se necessário:
```php
$host = 'localhost';
$dbname = 'controle_estoque';
$user = 'root';
$pass = ''; // Senha do MySQL (vazia no XAMPP)
```

### 5. Instale as dependências
```bash
# Entre na pasta do projeto
cd C:\xampp\htdocs\controle-estoque

# Instale o DOMPDF (para PDF)
php composer.phar require dompdf/dompdf

# Se não tiver o composer.phar, baixe primeiro
php -r "copy('https://getcomposer.org/composer.phar', 'composer.phar');"
```

### 6. Inicie o servidor
```bash
cd C:\xampp\htdocs\controle-estoque\public
php -S localhost:8080
```

### 7. Acesse o sistema
Abra o navegador e acesse: `http://localhost:8080`

---

## 📖 Como Usar

### **Primeiro Acesso**
1. Acesse `http://localhost:8080/inscreverse`
2. Crie uma nova conta (nome, email, senha)
3. Faça login com suas credenciais

### **Gerenciando Produtos**
- **Adicionar:** Clique em "Adicionar" no menu lateral
- **Listar:** Dashboard mostra todos os produtos
- **Filtrar:** Clique em "Filtrar" e selecione critérios
- **Editar:** Clique no ícone ✏️ do produto
- **Excluir:** Clique no ícone 🗑️ do produto (confirme)

### **Relatórios**
- Acesse "Relatórios" no menu lateral
- Visualize estatísticas completas
- Clique em "Exportar" e escolha o formato:
  - **CSV** - Para abrir no Excel
  - **Excel** - Formato XLS
  - **PDF** - Documento formatado

### **Feedback**
- Envie sugestões ou reporte bugs pela página "Feedback"
- O sistema envia um email com sua mensagem

---

## 📸 Capturas de Tela

*(Adicione aqui as imagens do seu sistema)*

### Tela de Login
![Login](screenshots/login.png)

### Dashboard
![Dashboard](screenshots/dashboard.png)

### Relatórios
![Relatórios](screenshots/relatorios.png)

### Exportação PDF
![PDF](screenshots/pdf.png)

---

## 🔮 Funcionalidades Futuras

- [ ] 📊 **Gráficos interativos** (Chart.js)
- [ ] 🔔 **Alertas de estoque mínimo** (notificações)
- [ ] 📈 **Histórico de movimentações** (entradas/saídas)
- [ ] 🏷️ **Múltiplos preços** (custo x venda)
- [ ] 📱 **Layout responsivo melhorado**
- [ ] 🔍 **Busca avançada com múltiplos critérios**
- [ ] 👥 **Múltiplos usuários com permissões**
- [ ] 📦 **Controle de fornecedores**
- [ ] 📎 **Upload de imagens para produtos**
- [ ] 📊 **Comparativo de períodos**

---

## 🤝 Contribuição

Contribuições são sempre bem-vindas! Se você tem alguma sugestão para melhorar o projeto:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

---

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 📫 Contato

**Mateus Silva** - [mateus@teste.com.br](mailto:mateus@teste.com.br)

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/seu-perfil)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/mateusilva)
[![Instagram](https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white)](https://instagram.com/seu-perfil)

---

## 🙏 Agradecimentos

- À comunidade Open Source por todas as bibliotecas incríveis
- A todos que testaram e contribuíram com feedbacks
- [FontAwesome](https://fontawesome.com/) pelos ícones
- [Bootstrap](https://getbootstrap.com/) pelo framework

---

**⭐️ Se este projeto te ajudou, dê uma estrela no GitHub!** ⭐️
