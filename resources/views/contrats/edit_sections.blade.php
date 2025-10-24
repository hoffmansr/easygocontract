@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Générer contrat — {{ $contrat->titre }}</h3>

  <div>
    <label for="modeleSelect">Choisir un modèle :</label>
    <select id="modeleSelect" class="form-select" style="width:400px;">
      <option value="">-- choisir --</option>
      @foreach($modeles as $m)
        <option value="{{ $m->id }}">{{ $m->nom }}</option>
      @endforeach
    </select>
  </div>

  <div class="mt-3">
    <iframe id="modele-contrat-frame" style="width:100%; height:600px; border:1px solid #ccc;"></iframe>
  </div>

  <!-- Modal Bootstrap -->
  <div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-section-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modal-section-content"></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button class="btn btn-primary" id="save-section-btn">Sauvegarder</button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
const contratId = {{ $contrat->id }};
let sectionsData = [];
let currentSection = null;

document.getElementById('modeleSelect').addEventListener('change', function(){
    const modeleId = this.value;
    if(!modeleId) return;
    fetch(`/modele-contrat/${modeleId}/sections`)
      .then(r=>r.json())
      .then(res=>{
          if(res.type !== 'sections'){ alert('Erreur chargement'); return; }
          sectionsData = res.sections;
          renderSectionsInIframe(sectionsData);
      });
});

function renderSectionsInIframe(sections){
    const iframe = document.getElementById('modele-contrat-frame');
    const doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write('<html><head><style>body{font-family:Arial;padding:18px}.section-block{position:relative;border:1px solid #eee;padding:12px;margin-bottom:12px;border-radius:6px}.btn-section-options{position:absolute;top:8px;right:8px;border:none;background:none;font-size:20px;cursor:pointer}</style></head><body>');
    sections.forEach(s=>{
        doc.write(`<div class="section-block" id="section-${s.index}">
            <button class="btn-section-options" onclick="parent.openSectionModal(${s.index})">⋮</button>
            ${s.html}
        </div>`);
    });
    doc.write('</body></html>');
    doc.close();
}

// Called from iframe button
function openSectionModal(index){
    currentSection = index;
    const s = sectionsData.find(x => x.index == index);
    if(!s) return alert('Section introuvable');

    // Build form from the HTML of the section (inputs have class variable-field)
    const temp = document.createElement('div');
    temp.innerHTML = s.html;
    const inputs = temp.querySelectorAll('.variable-field');

    // Request saved variables to prefill
    fetch(`/api/contrats/${contratId}/variables`).then(r=>r.json()).then(resp=>{
        const saved = resp.variables || {};
        let formHtml = '';
        inputs.forEach(inp=>{
            const name = inp.name;
            const value = saved[name] ?? '';
            formHtml += `<div class="mb-3"><label class="form-label">${name}</label>
                <input class="form-control" name="${name}" value="${value}"></div>`;
        });

        document.getElementById('modal-section-title').textContent = s.titre ?? ('Section '+index);
        document.getElementById('modal-section-content').innerHTML = formHtml;

        const modal = new bootstrap.Modal(document.getElementById('sectionModal'));
        modal.show();
    });
}

// Save action
document.getElementById('save-section-btn').addEventListener('click', ()=>{
    const formInputs = document.getElementById('modal-section-content').querySelectorAll('input');
    const payload = {};
    formInputs.forEach(i => payload[i.name] = i.value);

    fetch(`/contrats/${contratId}/save-section/${currentSection}`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify(payload)
    }).then(r=>r.json()).then(resp=>{
        alert(resp.message);
        // Update iframe visible inputs if any
        const iframe = document.getElementById('modele-contrat-frame');
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        Object.keys(payload).forEach(k=>{
            const el = doc.querySelector(`#section-${currentSection} input[name="${k}"]`);
            if(el) el.value = payload[k];
            // If inputs are replaced by spans in your implementation, update those accordingly.
        });
        bootstrap.Modal.getInstance(document.getElementById('sectionModal')).hide();
    });
});
</script>
@endsection
