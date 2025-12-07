# 🗓️ Camada User


## Funcionalidades Principais

* Cadastrar Login
* Cadastrar senha
* Dashboard de suas tarefas

---

## Funcionalidades Principais

## Arquitetura

```
/app
    /Config
        /Routes.php
    /database
        /Migratiorns
            /CreateUsersTable
   /Controllers
   /Models
   /Views
      /registerView
      /loginView
/public
   /assets
```

---

##  Routes

* $routes->get('register', 'RegisterController::index');
* $routes->post('register/store', 'RegisterController::store');

* $routes->get('login', 'LoginController::index');
* $routes->post('login/auth', 'LoginController::auth');
* $routes->get('logout', 'LoginController::logout');


## Banco de Dados


```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_login VARCHAR(255) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL
);
```


## Requisitos do Servidor

PHP **8.1 ou superior**, com:

* intl (Extensão php, necessário habilitar no código fonte ini.php)
* mbstring (Extensão php, necessário habilitar no código fonte ini.php)
* json
* curl
* mysqlnd (Extensão php, necessário habilitar no código fonte ini.php)

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
database.default.password = (Coloque sua senha aqui)
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

```
POST login/auth
```

Exemplo de resposta:

```json
[
  {
    "user_login": "Dev",
    "user_password": "Dev123",
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

## Controller

# RegisterController

```
 Function Store, que tem como principal criar o usuário, verificando se os campos estão preenchidos com IF e/ou se já existe aquele login. Posteriormente as verificações, inserimentos no banco de dados com o insert.
```

```function Store
    public function store(){
        $post = $this->request->getPost();

        if (empty($post['user_login']) || empty($post['user_password'])) {
            return redirect()->back()->with('error', 'Preencha todos os campos.')->withInput();
        }

        if ($this->userModel->where('user_login', $post['user_login'])->first()) {
            return redirect()->back()->with('error', 'Login já existe.')->withInput();
        }

        $this->userModel->insert([
            'user_login' => $post['user_login'],
            'user_password' => password_hash($post['user_password'], PASSWORD_DEFAULT)
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Conta criada.');
    }

```

# LoginController

```
 Function auth, com finalidade de validar as credencias, com um if de comparação, onde verificamos as credencias recebidas com credenciais já cadastradas no nosso banco, caso a validação tenha false como resposta, é emitido um erro para o usuário de credenciais invalidas.
```

``` public function auth(){
        $post = $this->request->getPost();

        if (empty($post['user_login']) || empty($post['user_password'])) {
            return redirect()->back()->with('error', 'Preencha login e senha.')->withInput();
        }

        $user = $this->userModel->where('user_login', $post['user_login'])->first();

        if (!$user || !password_verify($post['user_password'], $user['user_password'])) {
            return redirect()->back()->with('error', 'Credenciais inválidas.')->withInput();
        }

        $this->session->set([
            'user_id' => $user['user_id'],
            'user_login' => $user['user_login']
        ]);

        return redirect()->to(site_url('tasks'));
    }

```

```
 Function logout, função para interromper o acesso, caso queira sair de sua conta.
```

``` public function logout(){
        $this->session->destroy();
        return redirect()->to(site_url('/'));
    }

```
