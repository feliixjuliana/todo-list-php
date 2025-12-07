
# 🗓️ **Camada Front-End — Tasks**

A camada de interface de tarefas é responsável por:

* Exibir o **calendário interativo**
* Exibir a **lista de tarefas do usuário**
* Abrir o **modal de criação de novas tarefas**
* Atualizar status e excluir tarefas
* Enviar dados ao servidor através do formulário do modal

---

## 📁 Arquitetura do Front-End de Tasks

```
/app
   /Views
      /taskView.php
      /modals
          CreateTaskModal.php
/public
   /assets
      /calendar-04
         /css/style.css
         /js/main.js
      /js/tasks.js
```

---

## 🧱 View Principal das Tarefas — `taskView.php`

Arquivo: `/app/Views/taskView.php`

Esta view contém:

* Cabeçalho
* Calendário
* Lista lateral de tarefas
* Botão "Nova Tarefa"
* Inclusão do modal
* Inclusão do script `tasks.js`

```
<?= $this->extend('layouts/main_layout') ?>
<?= $this->section('conteudo') ?>

<h2 class="mb-3">Minhas Tarefas</h2>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= site_url('logout') ?>" class="btn btn-outline-secondary me-2">Sair</a>
    <button class="btn btn-primary" id="open-create">Nova Tarefa</button>
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

                <button class="button" id="add-button">Adicionar Evento</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <h5>Lista de Tarefas</h5>

            <ul class="list-group" id="task-list"></ul>
        </div>
    </div>

</div>

<?= $this->include('modals/CreateTaskModal') ?>

<script src="<?= base_url('assets/js/tasks.js') ?>"></script>

<?= $this->endSection() ?>
```

---

# 🪟 Modal de Criação de Tarefas — `CreateTaskModal.php`

Arquivo: `/app/Views/modals/CreateTaskModal.php`

```
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
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Data de Início</label>
            <input id="start_date" name="start_date" class="form-control" placeholder="AAAA-MM-DD HH:MM:SS">
          </div>

          <div class="mb-3">
            <label class="form-label">Data de Término</label>
            <input id="end_date" name="end_date" class="form-control" placeholder="AAAA-MM-DD HH:MM:SS">
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
```

---

# 🧠 Lógica Front-End — `tasks.js`

Arquivo: `/public/assets/js/tasks.js`

Responsável por:

* Abrir modal ao clicar no botão
* Carregar tarefas com fetch (`/tasks/events`)
* Atualizar lista de tarefas
* Selecionar o dia e preencher automaticamente o modal

```
(function () {

  function q(s) { return document.querySelector(s); }
  function qa(s) { return Array.from(document.querySelectorAll(s)); }

  let selectedDate = null;

  function fetchEvents() {
    fetch('/tasks/events')
      .then(r => r.json())
      .then(events => {
        window._ci_events = events;
        renderList(events);
        document.dispatchEvent(new Event('ciEventsLoaded'));
      });
  }

  function renderList(tasks) {
    let ul = q('#task-list');
    ul.innerHTML = '';

    if (!tasks.length) {
      ul.innerHTML =
        '<li class="list-group-item text-center">Nenhuma tarefa cadastrada</li>';
      return;
    }

    tasks.forEach(t => {
      let li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between';

      li.innerHTML = `
        <div>
          <strong>${t.title}</strong>
          <div class="small text-muted">${t.start}</div>
          <div class="small text-muted">${t.end}</div>
        </div>
      `;

      ul.appendChild(li);
    });
  }

  function initModal() {
    let modal = q('#createModal');
    let open = q('#open-create');
    let close1 = q('#close-create');
    let close2 = q('#close-create-2');

    function show() {
      modal.classList.add('show');
      modal.style.display = 'block';

      if (selectedDate) {
        q('#start_date').value = selectedDate + " 00:00:00";
        q('#end_date').value = selectedDate + " 00:00:00";
      }
    }

    function hide() {
      modal.classList.remove('show');
      modal.style.display = 'none';
    }

    open.addEventListener('click', show);
    close1.addEventListener('click', hide);
    close2.addEventListener('click', hide);
  }

  document.addEventListener('DOMContentLoaded', function () {

    qa('.tbody td').forEach(td => {
      td.addEventListener('click', () => {
        selectedDate = td.getAttribute('data-date');
      });
    });

    initModal();
    fetchEvents();
  });

})();
```

---

# 🔄 Fluxo do Usuário — Tarefas

1. Usuário acessa `/tasks`
2. Front-end chama `GET /tasks/events`
3. Lista é atualizada automaticamente
4. Usuário clica em um dia do calendário → `selectedDate` é definido
5. Usuário clica em **Nova Tarefa**
6. Modal abre com a data preenchida automaticamente
7. Formulário envia para `POST /tasks/store`
8. O servidor salva no banco
9. página recarrega e lista atualiza novamente

---

# 📌 Endpoints Consumidos pelo Front-End

### ✔️ `GET /tasks/events`

Retorna todas as tarefas do usuário autenticado.

Exemplo:

```json
[
  {
    "id": 1,
    "title": "Lavar os pratos",
    "start": "2025-12-10 10:00:00",
    "end": "2025-12-10 11:00:00",
    "status": "pendente"
  }
]
```
