<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="site-url" content="<?= base_url() ?>">
    <title>Minhas Tarefas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/calendar-04/css/style.css') ?>">
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Minhas Tarefas</h3>

        <div>
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-secondary me-2">Sair</a>
            <button class="btn btn-primary" id="open-create">Nova Tarefa</button>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
            <div class="calendar-container">
                <div class="calendar">

                    <div class="year-header">
                        <span class="left-button fa fa-chevron-left" id="prev"></span>
                        <span class="year" id="label"></span>
                        <span class="right-button fa fa-chevron-right" id="next"></span>
                    </div>

                    <table class="months-table w-100">
                        <tbody>
                            <tr class="months-row">
                                <td class="month active-month">Jan</td>
                                <td class="month">Fev</td>
                                <td class="month">Mar</td>
                                <td class="month">Abr</td>
                                <td class="month">Mai</td>
                                <td class="month">Jun</td>
                                <td class="month">Jul</td>
                                <td class="month">Ago</td>
                                <td class="month">Set</td>
                                <td class="month">Out</td>
                                <td class="month">Nov</td>
                                <td class="month">Dez</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="days-table w-100">
                        <tr>
                            <td class="day">Dom</td>
                            <td class="day">Seg</td>
                            <td class="day">Ter</td>
                            <td class="day">Qua</td>
                            <td class="day">Qui</td>
                            <td class="day">Sex</td>
                            <td class="day">Sáb</td>
                        </tr>
                    </table>

                    <div class="frame">
                        <table class="dates-table w-100">
                            <tbody class="tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Lista de Tarefas</h5>
                <ul class="list-group mt-3" id="task-list">
                    <?php if (!empty($tasks) && is_array($tasks)) : ?>
                        <?php foreach ($tasks as $task) : ?>
                            
                            <?php 
                                $finalizada = ($task['status'] == 'completado');

                                $estiloTexto = $finalizada ? 'text-decoration-line-through text-muted' : 'fw-bold';
                            ?>

                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                
                                <div class="d-flex align-items-center gap-3">
                                    
                                    <form action="<?= site_url('tasks/' . $task['task_id'] . '/status') ?>" method="post">
                                        
                                        <?php if ($finalizada) : ?>
                                            <input type="hidden" name="status" value="pendente">
                                            <button type="submit" class="btn btn-success btn-sm" title="Desmarcar">
                                                &#9745; </button>
                                        <?php else : ?>
                                            <input type="hidden" name="status" value="completado">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm" title="Concluir">
                                                &#9744; </button>
                                        <?php endif; ?>
                                    
                                    </form>

                                    <div class="<?= $estiloTexto ?>">
                                        <?= esc($task['name']) ?>
                                        <br>
                                        <small style="font-size: 0.8em; font-weight: normal;">
                                            <?= date('d/m H:i', strtotime($task['start_date'])) ?>
                                        </small>
                                    </div>

                                </div>

                                <form action="<?= site_url('tasks/' . $task['task_id'] . '/delete') ?>" method="post" onsubmit="return confirm('Apagar esta tarefa?');">
                                    <button type="submit" class="btn btn-light text-danger btn-sm border-0" title="Excluir">
                                        &#10005; </button>
                                </form>

                            </li>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <li class="list-group-item text-center text-muted p-4">
                            Nenhuma tarefa pendente. <br> Clique em "Nova Tarefa" para começar!
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<?= view('modals/CreateTaskModal') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/calendar-04/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/calendar-04/js/main.js') ?>"></script>
<script src="<?= base_url('assets/js/tasks.js') ?>"></script>

</body>
</html>
