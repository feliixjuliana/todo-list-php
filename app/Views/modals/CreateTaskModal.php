<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="post" action="<?= site_url('tasks/store') ?>">
        <?= csrf_field() ?>

        <div class="modal-header">
          <h5 class="modal-title">Criar Nova Tarefa</h5>
          <button type="button" class="btn-close" id="close-create"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="name" class="form-control" required placeholder="Digite o título da tarefa">
          </div>

          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Digite a descrição"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Data de Início</label>
            <input id="start_date" name="start_date" type="datetime-local" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Data de Término</label>
            <input id="end_date" name="end_date" type="datetime-local" class="form-control">
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
