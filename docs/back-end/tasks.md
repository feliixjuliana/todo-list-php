# 🗓️ Camada Tasks

## Funcionalidades Principais

* Criar tarefas
* Listar tarefas
* Atualizar status (pendente, completado ou cancelado)
* Excluir tarefas
* Carregamento de tarefas via JSON

---

## Arquitetura

```
/app
    /Config
        Routes.php
    /Database
        /Migrations
            CreateTasksTable.php
    /Controllers
        TaskController.php
    /Models
        TaskModel.php
    /Views
        taskView.php
        /modals
            CreateTaskModal.php
/public
   /assets
       /calendar-04
       /js
           tasks.js
```

---

## Routes

* $routes->get('tasks', 'TaskController::index');
* $routes->post('tasks/store', 'TaskController::store');
* $routes->post('tasks/(:num)/status', 'TaskController::updateStatus/$1');
* $routes->post('tasks/(:num)/delete', 'TaskController::delete/$1');
* $routes->get('tasks/events', 'TaskController::eventsJson');

---

## Banco de Dados

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

---

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

---

### 2. Crie o banco:

```sql
CREATE DATABASE tarefas_db
```

---

### 3. Configure o `.env`:

```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = tarefas_db
database.default.username = root
database.default.password = (Coloque sua senha aqui)
database.default.DBDriver = MySQLi
```

---

### 4. Execute as migrations:

```sh
php spark migrate
```

---

### 5. Inicie o servidor:

```sh
php spark serve
```

Acesse:

👉 [http://localhost:8080](http://localhost:8080)

---

## Endpoints Importantes

```
POST /tasks/store
```

Exemplo de envio:

```json
[
  {
    "name": "Estudar PHP",
    "description": "Rotina de estudo",
    "start_date": "2025-12-10 09:00:00",
    "end_date": "2025-12-10 10:00:00",
    "status": "pendente"
  }
]
```

---

## Fluxo do Usuário

1. Acessar tarefas (requer login)
2. Criar tarefa pelo modal
3. Atualizar o status
4. Excluir quando desejar

---

# Controller

# TaskController

```
 Função index, responsável por carregar todas as tarefas do usuário logado e retornar para a view principal do calendário e da lista lateral.
```

```
public function index(){
    $userId = $this->session->get('user_id');
    if (!$userId) return redirect()->to(site_url('login'));

    $tasks = $this->taskModel->where('user_id', $userId)->findAll();
    return view('taskView', ['tasks' => $tasks]);
}
```

---

```
 Função store, responsável por criar uma nova tarefa do usuário, recebendo os dados do formulário do modal e inserindo no banco.
```

```
public function store(){
    $userId = $this->session->get('user_id');
    if (!$userId) return redirect()->to(site_url('login'));

    $post = $this->request->getPost();

    $this->taskModel->insert([
        'user_id' => $userId,
        'name' => $post['name'],
        'description' => $post['description'],
        'start_date' => $post['start_date'],
        'end_date' => $post['end_date'],
        'status' => 'pendente'
    ]);

    return redirect()->to(site_url('tasks'));
}
```

---

```
 Função updateStatus, responsável por alterar o status da tarefa para pendente, completo ou cancelado.
```

```
public function updateStatus($id){
    $userId = $this->session->get('user_id');
    if (!$userId) return redirect()->to(site_url('login'));

    $task = $this->taskModel->find($id);
    if (!$task || $task['user_id'] != $userId) return redirect()->back();

    $newStatus = $this->request->getPost('status');

    $this->taskModel->update($id, [
        'status' => $newStatus
    ]);

    return redirect()->to(site_url('tasks'));
}
```

---

```
 Função delete, que apaga a tarefa definitivamente do banco, somente se pertencer ao usuário logado.
```

```
public function delete($id){
    $userId = $this->session->get('user_id');
    if (!$userId) return redirect()->to(site_url('login'));

    $task = $this->taskModel->find($id);
    if (!$task || $task['user_id'] != $userId) return redirect()->back();

    $this->taskModel->delete($id);
    return redirect()->to(site_url('tasks'));
}
```

---

```
 Função eventsJson, utilizada para devolver as tarefas em formato JSON para o calendário e para o script tasks.js.
```

```
public function eventsJson(){
    $userId = $this->session->get('user_id');
    if (!$userId) return $this->response->setJSON([]);

    $tasks = $this->taskModel->where('user_id', $userId)->findAll();

    $events = [];
    foreach ($tasks as $t) {
        $events[] = [
            'id' => $t['task_id'],
            'title' => $t['name'],
            'start' => $t['start_date'],
            'end' => $t['end_date'],
            'status' => $t['status']
        ];
    }

    return $this->response->setJSON($events);
}
```

---

# Modelo (Model)

```
 O modelo é responsável por mapear a tabela tasks dentro do CodeIgniter,
 permitindo inserção, consulta, atualização e exclusão das tarefas.
```

```
class TaskModel extends Model {
    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $allowedFields = [
        'user_id','name','description','start_date','end_date','status'
    ];
}
```
