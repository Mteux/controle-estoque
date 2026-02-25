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
