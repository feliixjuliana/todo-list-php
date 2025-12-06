<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>

    <meta name="base-url" content="<?= base_url() ?>">
    <meta name="site-url" content="<?= site_url('/') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site.css') ?>">
</head>
<body>

    <?= $this->renderSection('conteudo') ?>

    <script src="<?= base_url('assets/jquery.slim.min.js') ?>"></script>
    <script src="<?= base_url('assets/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap.min.js') ?>"></script>

</body>
</html>
