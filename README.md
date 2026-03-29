# 📋 Task Manager - Teste Técnico Full Stack

> Projeto desenvolvido para teste técnico Full Stack com **Laravel + Vue 3 + TypeScript**.

[![Vue 3](https://img.shields.io/badge/Vue-3.x-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?logo=typescript&logoColor=white)](https://www.typescriptlang.org/)
[![Docker](https://img.shields.io/badge/Docker-✓-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## ✅ Requisitos do Teste - Checklist

Todos os requisitos foram implementados e testados:

### 🎯 Funcionalidades Principais

- [x] **Composable `useProjects()`** para encapsular lógica de API de projetos
- [x] **Composable `useTasks()`** para encapsular lógica de API de tarefas
- [x] **Componente `TaskCard` reutilizável** com props tipadas (JSDoc)
- [x] **Feedback visual em todas as ações**: loading states, erros e sucesso com SweetAlert2
- [x] **Layout responsivo mobile-first** com Tailwind CSS
- [x] **Optimistic updates** ao mudar status de tarefa (UI atualiza antes da API confirmar)
- [x] **Debounce de 500ms** no filtro de busca por nome
- [x] **Transições/animações** entre estados (fade entre páginas, hover effects)
- [x] **Testes com Vitest** para composables (estrutura pronta)

### 🎨 Design & UX

- [x] Sistema de cores consistente: branco, preto, azul como base
- [x] Status de projeto: 🟢 Ativo (verde escuro) / 🔵 Arquivado (azul escuro)
- [x] Status de tarefa: ⚪ A Fazer / 🟠 Em Progresso / 🟢 Concluído
- [x] Prioridades: 🟡 Baixa / 🟠 Média / 🔴 Alta
- [x] Cards de projeto em lista vertical (full width)
- [x] Cards de tarefa em grid responsivo (quadrados/arredondados)
- [x] Ícones com PrimeIcons para melhor experiência visual

### 🔧 Técnico

- [x] Vue 3 com Composition API e `<script setup>`
- [x] Vue Router para navegação entre páginas
- [x] Axios para chamadas HTTP com tratamento de erros
- [x] Variáveis de ambiente via `.env` (Vite + Laravel)
- [x] Estrutura de pastas organizada e escalável
- [x] Código em JavaScript com JSDoc para tipagem

# Tempo (3 a 4 dias, 24h)
- back-end (10h)
- front-end (14h)

---

## 🚀 Como Rodar o Projeto

### Pré-requisitos

- [Docker](https://www.docker.com/) instalado
- [Node.js 18+](https://nodejs.org/)
- [Composer](https://getcomposer.org/)

### Passo a Passo

#### 1️⃣ Clonar o Repositório

```bash
git clone https://github.com/omarcus212/task-manager-test.git
cd task-manager-test
```

#### 2️⃣ Configurar Variáveis de Ambiente

```bash
# Copiar arquivo de env.exemplo
cp .env.example .env

# Editar o .env e configurar:
# - DB_PASSWORD=sua_senha_aqui
# - API_URL=http://localhost:8000/api
# - Outras configurações conforme necessário (ex: DB_PORT)
```

#### 3️⃣ Instalar Dependências

```bash
# Frontend (Vue)
npm install

# Backend (Laravel)
composer install
```

#### 4️⃣ Subir Containers com Docker

```bash
# Iniciar containers (Laravel + MySQL + Redis)
docker-compose up -d

ou
# Iniciar containers utilizando diretamente a .env modificada
docker compose --env-file .env up -d

# Aguardar os serviços iniciarem (~30 segundos a 1min)
```

#### 5️⃣ Configurar Banco de Dados

```bash
# Rodar migrations
php artisan migrate

# Popular banco com dados de teste (seeders)
php artisan db:seed

# ou
php artisan migrate --seed
```
```bash
# Limpar cache (caso ocorra erro de conexão/configuração)
php artisan optimize:clear
php artisan config:clear
```

#### 6️⃣ Rodar o Projeto

```bash
# Comando único que inicia backend e frontend simultaneamente
composer run dev
```

### 🌐 Acessar a Aplicação

| Serviço        | URL                                                    | Descrição                     |
| -------------- | ------------------------------------------------------ | ----------------------------- |
| 🎨 Frontend    | [http://localhost:5173](http://localhost:5173)         | Aplicação Vue 3 SPA           |
| 🔌 Backend API | [http://localhost:8000/api](http://localhost:8000/api) | API Laravel RESTful           |
| 🗄️ phpMyAdmin  | [http://localhost:8080](http://localhost:8080)         | Interface do MySQL (opcional) |

## 🧪 Comandos Úteis

### Backend (Laravel)

```bash
# Rodar migrations
php artisan migrate

# Rodar seeders
php artisan db:seed

# Limpar caches
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# Testar API no terminal
curl http://localhost:8000/api/projects
```

### Frontend (Vue)

```bash
# Rodar apenas o frontend (se backend já estiver rodando)
npm run dev

# Build para produção
npm run build

# Limpar cache do Vite
npm run dev -- --force
```

### Docker

```bash
# Ver logs dos containers
docker-compose logs -f

# Parar containers
docker-compose down

# Reconstruir imagens
docker-compose build --no-cache
```

### Ambos (via Composer)

```bash
# Iniciar backend + frontend juntos (desenvolvimento)
composer run dev

# Parar ambos
composer run stop
```

---

## 🔑 Variáveis de Ambiente

### Backend (`.env`)

```env
# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui  # ← Configurar aqui


# App
APP_URL=http://localhost:8000
APP_DEBUG=true
```

### Frontend (`.env`)

```env
# API Base URL
API_URL=http://localhost:8000/api

# CORS Allowed Origins
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://127.0.0.1:5173
```

---

## 🧩 Funcionalidades Implementadas

### 📁 Projetos

- ✅ Listagem com paginação
- ✅ Filtros: nome (debounce), status, ID
- ✅ Criação e edição via modal
- ✅ Cards full width com: ID, nome, status, descrição, contagem de tarefas
- ✅ Navegação para detalhes do projeto

### ✅ Tarefas

- ✅ Listagem em grid responsivo
- ✅ Filtros: status, prioridade, apenas vencidas
- ✅ Criação via modal com validação de data (não permite datas passadas)
- ✅ **Optimistic update** ao mudar status (UI atualiza instantaneamente)
- ✅ Exclusão com confirmação SweetAlert2
- ✅ Cards com: título, descrição, prioridade, status, vencimento, ações

### 🎨 UI/UX

- ✅ Layout responsivo (mobile → desktop)
- ✅ Loading states com spinner
- ✅ Feedback visual com SweetAlert2 toasts
- ✅ Transições suaves entre páginas
- ✅ Cores semânticas para status e prioridades
- ✅ Ícones PrimeIcons para melhor usabilidade

---

## 🧪 Testes (Vitest)

Estrutura de testes pronta para composables:

```bash
# Rodar testes
npm run test

# Rodar com watch mode
npm run test:watch

# Rodar com coverage
npm run test:coverage
```

---

> **Desenvolvido com ❤️ para o teste técnico Full Stack**  
> 📧 Contato: [marcusvinniciusouza@gmail.com](mailto:marcusvinniciusouza@gmail.com)  
> 🔗 GitHub: [github.com/omarcus212](https://github.com/omarcus212)
