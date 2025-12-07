# 🗓️ Agenda Eletrônica — Sistema de Tarefas

## O que é este projeto?

A **Agenda Eletrônica** é um sistema completo de gerenciamento de tarefas, desenvolvido com **PHP (CodeIgniter 4.6)**, **MySQL**, **Bootstrap**, **JavaScript** e um calendário interativo personalizado.

Cada usuário visualiza **somente suas próprias tarefas**, garantindo segurança e isolamento de dados.

---

## Funcionalidades Principais

* Cadastro de usuários
* Login / Logout
* Criação de tarefas
* Listagem de tarefas
* Envio e recebimento de dados via JSON

---

## Calendário e Modal

* Tarefas são carregadas via `/tasks/events` em formato JSON
* Lista lateral e calendário consomem o mesmo endpoint
* Interface totalmente responsiva feita com Bootstrap 5

---

## Estrutura do Projeto (Resumo)

```
/app
   /Controllers
   /Models
   /Views
/public
   /assets
      /calendar-04
      /js
      /css
```

---

## Banco de Dados

As migrations criam duas tabelas:

### `users`

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_login VARCHAR(255) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL
);
```

### `tasks`

```sql
CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    status ENUM('pendente','completado','cancelado') DEFAULT 'pendente',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

## Requisitos do Servidor

PHP **8.1 ou superior**, com:

* intl
* mbstring
* json
* curl
* mysqlnd

MySQL 5.7+ recomendado.

---

## Instalação

### 1. Clone o repositório:

```sh
git clone https://github.com/feliixjuliana/todo-list-php.git
cd todo-list-php
```

### 2. Crie o banco:

```sql
CREATE DATABASE tarefas_db
```

### 3. Configure o `.env`:

```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = tarefas_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 4. Execute as migrations:

```sh
php spark migrate
```

### 5. Inicie o servidor:

```sh
php spark serve
```

Acesse:

👉 [http://localhost:8080](http://localhost:8080)

---

## Endpoints Importantes

### Retorna todas as tarefas do usuário logado:

```
GET /tasks/events
```

Exemplo de resposta:

```json
[
  {
    "id": 3,
    "title": "Lavar os pratos",
    "description": "Pratos apenas da tarde",
    "start": "2025-12-12 10:30:00",
    "end": "2025-12-12 12:00:00",
    "status": "pendente"
  }
]
```

---

## Fluxo do Usuário

1. Registrar
2. Logar
3. Acessar calendário
4. Criar tarefa
5. Editar status ou excluir

---

## Melhorias Futuras

* Edição completa da tarefa
* Busca por filtros
* Estatísticas do usuário
* Agenda semanal
* Notificações

