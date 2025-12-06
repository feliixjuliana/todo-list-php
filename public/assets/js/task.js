(function () {
  function meta(name) {
    var el = document.querySelector('meta[name="' + name + '"]');
    return el ? el.getAttribute('content') : '';
  }

  var siteUrl = meta('site-url') || '/';
  if (siteUrl.slice(-1) !== '/') siteUrl += '/';

  var STATUS_LABEL = {
    'pendente': 'Pendente',
    'completado': 'Concluído',
    'cancelado': 'Cancelado'
  };

  function q(s){ return document.querySelector(s); }
  function qa(s){ return Array.prototype.slice.call(document.querySelectorAll(s)); }

  function localizeCalendarLabels() {
    var meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    var dias =  ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

    qa('.months-table .month').forEach(function(td, i){
      td.textContent = meses[i];
    });

    qa('.days-table .day').forEach(function(td, i){
      td.textContent = dias[i];
    });
  }

  function exibirStatus(raw, endDate) {
    raw = String(raw || '').trim().toLowerCase();

    if (raw === 'pendente' && endDate) {
      var d = new Date(endDate.replace(' ', 'T'));
      if (!isNaN(d.getTime()) && d < new Date()) {
        return 'Atrasado';
      }
    }

    return STATUS_LABEL[raw] || raw;
  }

  function fetchEvents() {
    fetch(siteUrl + 'tasks/events')
      .then(r => r.json())
      .then(events => {
        var normalizados = events.map(e => ({
          id: e.task_id || e.id,
          title: e.name || '',
          start: e.start_date || '',
          end: e.end_date || '',
          status: e.status
        }));

        window._ci_events = normalizados;

        renderList(normalizados);

        document.dispatchEvent(new Event('ciEventsLoaded'));
      });
  }

  function renderList(tasks) {
    var ul = q('#task-list');
    if (!ul) return;
    ul.innerHTML = '';

    if (!tasks.length) {
      ul.innerHTML = '<li class="list-group-item text-center">Nenhuma tarefa ainda</li>';
      return;
    }

    tasks.forEach(t => {
      var li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between';

      var left = document.createElement('div');
      left.innerHTML = `
        <strong>${t.title}</strong>
        <div class="small text-muted">${t.start} — ${t.end}</div>
        <div class="small">Status: ${exibirStatus(t.status, t.end)}</div>
      `;

      var right = document.createElement('div');

      var f1 = document.createElement('form');
      f1.method = 'post';
      f1.action = siteUrl + 'tasks/' + t.id + '/status';
      f1.innerHTML = `
        <?= csrf_field() ?>
        <select name="status" class="form-select form-select-sm">
          <option value="pendente" ${t.status === 'pendente' ? 'selected' : ''}>Pendente</option>
          <option value="completado" ${t.status === 'completado' ? 'selected' : ''}>Concluído</option>
          <option value="cancelado" ${t.status === 'cancelado' ? 'selected' : ''}>Cancelado</option>
        </select>
        <button class="btn btn-sm btn-outline-primary w-100 mt-1">Atualizar</button>
      `;

      var f2 = document.createElement('form');
      f2.method = 'post';
      f2.action = siteUrl + 'tasks/' + t.id + '/delete';
      f2.innerHTML = `
        <?= csrf_field() ?>
        <button class="btn btn-sm btn-outline-danger w-100 mt-1">Excluir</button>
      `;

      right.appendChild(f1);
      right.appendChild(f2);

      li.appendChild(left);
      li.appendChild(right);
      ul.appendChild(li);
    });
  }

  function initModal() {
    var modal = q('#createModal');
    var open = q('#open-create');
    var close1 = q('#close-create');
    var close2 = q('#close-create-2');

    function show() { modal.style.display = 'block'; modal.classList.add('show'); }
    function hide() { modal.style.display = 'none'; modal.classList.remove('show'); }

    if (open) open.addEventListener('click', show);
    if (close1) close1.addEventListener('click', hide);
    if (close2) close2.addEventListener('click', hide);
  }

  document.addEventListener('DOMContentLoaded', function(){
    localizeCalendarLabels();
    initModal();
    fetchEvents();
  });

})();
