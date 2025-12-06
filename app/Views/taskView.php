<!doctype html>
<html lang="pt-BR">
<head>
    <title>Calendário de Tarefas</title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/calendar-04/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/site.css') ?>">
</head>

<body>

<section class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Calendário de Tarefas</h2>
        <div>
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-secondary">Sair</a>
            <button class="btn btn-primary" id="open-create">Adicionar Tarefa</button>
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
                                <td class="month">Jan</td>
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
                        <td class="day">Dom</td>
                        <td class="day">Seg</td>
                        <td class="day">Ter</td>
                        <td class="day">Qua</td>
                        <td class="day">Qui</td>
                        <td class="day">Sex</td>
                        <td class="day">Sáb</td>
                    </table>

                    <div class="frame">
                        <table class="dates-table w-100">
                            <tbody class="tbody"></tbody>
                        </table>
                    </div>

                    <button class="button" id="add-button">Adicionar</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Minhas tarefas</h5>

                <ul class="list-group" id="task-list">

                    <?php if (!empty($tasks)): ?>
                        <?php foreach($tasks as $t): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">

                                <div>
                                    <strong><?= esc($t['name']) ?></strong>
                                    <div class="small text-muted">
                                        <?= esc($t['start_date']) ?> — <?= esc($t['end_date']) ?>
                                    </div>
                                    <div class="small">Status: 
                                        <?php
                                            $status = strtolower(trim($t['status']));
                                            if ($status === 'pendente' && !empty($t['end_date']) && strtotime($t['end_date']) < time()) {
                                                echo 'Atrasado';
                                            } else {
                                                if ($status === 'pendente') echo 'Pendente';
                                                elseif ($status === 'completado') echo 'Concluído';
                                                elseif ($status === 'cancelado') echo 'Cancelado';
                                                else echo esc($t['status']);
                                            }
                                        ?>
                                    </div>
                                </div>

                                <div>
                                    <form action="<?= site_url('tasks/' . $t['task_id'] . '/status') ?>" method="post" class="mb-2">
                                        <?= csrf_field() ?>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pendente" <?= $t['status']=='pendente'?'selected':'' ?>>Pendente</option>
                                            <option value="completado" <?= $t['status']=='completado'?'selected':'' ?>>Concluído</option>
                                            <option value="cancelado" <?= $t['status']=='cancelado'?'selected':'' ?>>Cancelado</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary w-100 mt-1">Atualizar</button>
                                    </form>

                                    <form action="<?= site_url('tasks/' . $t['task_id'] . '/delete') ?>" method="post" onsubmit="return confirm('Deseja realmente excluir esta tarefa?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger w-100 mt-1">Excluir</button>
                                    </form>
                                </div>

                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center">Nenhuma tarefa ainda</li>
                    <?php endif; ?>

                </ul>

            </div>
        </div>

    </div>
</section>


<div class="modal" tabindex="-1" id="createModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="post" action="<?= site_url('tasks/store') ?>">
        <?= csrf_field() ?>

        <div class="modal-header">
          <h5 class="modal-title">Criar Tarefa</h5>
          <button type="button" class="btn-close" id="close-create"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label>Nome</label>
            <input name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Descrição</label>
            <textarea name="description" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label>Data de início (AAAA-MM-DD HH:MM:SS)</label>
            <input name="start_date" class="form-control" placeholder="2025-12-01 14:00:00">
          </div>

          <div class="mb-3">
            <label>Data de término (AAAA-MM-DD HH:MM:SS)</label>
            <input name="end_date" class="form-control" placeholder="2025-12-01 15:00:00">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" id="close-create-2" class="btn btn-secondary">Cancelar</button>
          <button class="btn btn-primary">Criar</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script src="<?= base_url('assets/calendar-04/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/calendar-04/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/calendar-04/js/main.js') ?>"></script>

<script src="<?= base_url('assets/js/tasks.js') ?>"></script>

</body>
</html>
