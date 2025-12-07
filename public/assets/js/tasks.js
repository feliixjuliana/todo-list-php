(function () {
  function q(s){ return document.querySelector(s); }
  function qa(s){ return Array.from(document.querySelectorAll(s)); }
  function pad(n){ return String(n).padStart(2,'0'); }

  const baseUrl = document.querySelector('meta[name="site-url"]').content;

  function isoFromParts(year, monthZeroBased, day, hour, minute){
    return `${year}-${pad(monthZeroBased+1)}-${pad(day)}T${pad(hour||9)}:${pad(minute||0)}`;
  }

  function openModal(){ const m=q('#createModal'); if(!m) return; m.classList.add('show'); m.style.display='block'; }
  function closeModal(){ const m=q('#createModal'); if(!m) return; m.classList.remove('show'); m.style.display='none'; }

  async function carregarTarefas() {
      try {
          const resposta = await fetch(`${baseUrl}/tasks/events`);
          
          const tarefas = await resposta.json();

          const listaElemento = q('#task-list');
          listaElemento.innerHTML = ''; 

          tarefas.forEach(tarefa => {
              const item = document.createElement('li');
              item.className = 'list-group-item d-flex justify-content-between align-items-center';
              
              let corStatus = tarefa.status === 'pendente' ? 'text-warning' : 'text-success';

              item.innerHTML = `
                  <div>
                      <strong>${tarefa.title}</strong><br>
                      <small class="text-muted">Início: ${tarefa.start}</small>
                  </div>
                  <span class="${corStatus}">${tarefa.status}</span>
              `;

              listaElemento.appendChild(item);
          });

      } catch (erro) {
          console.error("Erro ao buscar tarefas:", erro);
      }
  }

  document.addEventListener('DOMContentLoaded', function(){
    const openBtn = q('#open-create');
    const close1 = q('#close-create');
    const close2 = q('#close-create-2');
    
    if(openBtn) openBtn.addEventListener('click', openModal);
    if(close1) close1.addEventListener('click', closeModal);
    if(close2) close2.addEventListener('click', closeModal);

    carregarTarefas(); 

    function attach(){
      qa('.dates-table td').forEach(td => {
        td.addEventListener('click', function(){
             const dayText = this.textContent.trim();
             if(!dayText) return;
             openModal();
        });
      });
    }

    document.addEventListener('ciCalendarRendered', attach);
    setTimeout(attach, 300);
  });
})();