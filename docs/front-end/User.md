# **Camada Front-End — Usuário**

A camada de interface do usuário é responsável por:

* Tela de **registro**
* Tela de **login**
* Exibição de mensagens de erro e sucesso
* Estrutura visual padrão (layout base)
* Navegação entre as páginas

---

## ⚙️ **Arquitetura das Views**

```
/app
   /Views
      /layouts
         main_layout.php
      registerView.php
      loginView.php
/public
   /assets
      bootstrap.min.css
      bootstrap.min.js
      jquery.slim.min.js
      popper.min.js
      site.css
```

---

## **Layout Base (`main_layout.php`)**

Arquivo: `/app/Views/layouts/main_layout.php`
Responsável por:

* Navbar
* Estrutura HTML padrão
* Importação do Bootstrap
* Carregamento do JS e CSS
* Template system do CodeIgniter (`renderSection`)

```php
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="site-url" content="<?= site_url('/') ?>">
  <title><?= $title ?? "Aplicação" ?></title>

  <link href="<?= base_url('assets/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/site.css') ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-light bg-light px-3">
  <a class="navbar-brand" href="<?= site_url('/') ?>">TodoApp</a>

  <div>
    <?php if(session()->get('user_id')): ?>
      <a class="btn btn-outline-primary" href="<?= site_url('tasks') ?>">Tarefas</a>
      <a class="btn btn-danger" href="<?= site_url('logout') ?>">Sair</a>
    <?php else: ?>
      <a class="btn btn-outline-primary" href="<?= site_url('login') ?>">Login</a>
      <a class="btn btn-success" href="<?= site_url('register') ?>">Registrar</a>
    <?php endif; ?>
  </div>
</nav>

<main class="container mt-4">
  <?= $this->renderSection('conteudo') ?>
</main>

<script src="<?= base_url('assets/jquery.slim.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap.min.js') ?>"></script>
</body>
</html>
```

---

## **Tela de Registro (`registerView.php`)**

Arquivo: `/app/Views/registerView.php`

Funcionalidade:

* Formulário de criação de conta
* Exibição de mensagens de validação (flashdata)
* Envio para `register/store`

```php
<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('conteudo') ?>

<div class="col-md-6 mx-auto">

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">Criar Conta</div>

    <div class="card-body">
      <form method="post" action="<?= site_url('register/store') ?>">
        <?= csrf_field() ?>

        <label class="form-label">Login</label>
        <input type="text" name="user_login" class="form-control" required>

        <label class="form-label mt-3">Senha</label>
        <input type="password" name="user_password" class="form-control" required>

        <button class="btn btn-primary w-100 mt-3">Registrar</button>
      </form>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
```

---

## **Tela de Login (`loginView.php`)**

Arquivo: `/app/Views/loginView.php`

Funcionalidade:

* Autenticação do usuário
* Verificação de credenciais inválidas
* Redirecionamento para `/tasks`

```php
<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('conteudo') ?>

<div class="col-md-6 mx-auto">

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">Login</div>

    <div class="card-body">
      <form method="post" action="<?= site_url('login/auth') ?>">
        <?= csrf_field() ?>

        <label class="form-label">Login</label>
        <input type="text" name="user_login" class="form-control" required>

        <label class="form-label mt-3">Senha</label>
        <input type="password" name="user_password" class="form-control" required>

        <button class="btn btn-primary w-100 mt-3">Entrar</button>
      </form>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
```

---

# Fluxo do Front-End (Usuário)

1. Usuário acessa `/register`
2. Preenche login e senha
3. Sistema chama `RegisterController::store`
4. Usuário é redirecionado para `/login`
5. Após logar, é enviado para `/tasks`

