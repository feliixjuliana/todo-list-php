<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('conteudo') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card p-4 shadow-sm">
                <h3 class="mb-3">Criar Conta</h3>

                <form action="<?= site_url('register/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label>Login</label>
                        <input name="user_login" class="form-control" value="<?= old('user_login') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Senha</label>
                        <input name="user_password" type="password" class="form-control" required>
                    </div>

                    <button class="btn btn-success w-100">Cadastrar</button>

                    <div class="mt-3 text-center">
                        <a href="<?= site_url('login') ?>">Já tem conta? Entrar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
