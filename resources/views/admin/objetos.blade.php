@extends('layouts.app')
@section('title', 'Admin Objetos — Looking For')
@section('nav-admin', 'active')

@section('content')
<section class="section">
    <div class="section__header"><h1 class="section__title"><i class="fas fa-box-open"></i> Gestión de Objetos</h1></div>
    <div class="section__toolbar">
        <div class="search-bar"><i class="fas fa-search"></i><input type="text" id="sO" placeholder="Buscar..."></div>
        <select class="form-control" id="fTipo" style="width:auto;max-width:200px">
            <option value="">Todos los tipos</option>
            <option>Personal</option><option>Material de Estudio</option><option>Tecnológico</option>
        </select>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr>
        <th>Img</th><th>Nombre</th><th>Tipo</th><th>Lugar</th><th>Fecha</th><th>Usuario</th><th>Acciones</th>
    </tr></thead><tbody id="tBody"></tbody></table></div>
    <div id="pag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();let url=`/admin/objetos?page=${p}`;
const t=$('#fTipo').val();const b=$('#sO').val();if(t)url+=`&tipo=${t}`;if(b)url+=`&buscar=${b}`;
try{const r=await API.get(url);let h='';r.data.forEach(o=>{h+=`<tr>
<td>${o.imagen?`<img src="${o.imagen}">`:'—'}</td><td>${o.nombre}</td>
<td><span class="card__badge ${badgeClass(o.tipo)}">${o.tipo}</span></td>
<td>${o.lugar}</td><td>${formatDate(o.fecha)}</td><td>${o.user?.nombre||'—'}</td>
<td><button class="btn btn--outline btn--sm" onclick="edit(${o.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${o.id})"><i class="fas fa-trash"></i></button></td></tr>`;});
$('#tBody').html(h||'<tr><td colspan="7" style="text-align:center;padding:2rem">Sin datos</td></tr>');
$('#pag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

async function edit(id){showLoading();try{const r=await API.get(`/admin/objetos/${id}`);
openModal('Editar Objeto',`<form id="oF">
<div class="form-group"><label>Nombre</label><input class="form-control" name="nombre" value="${r.data.nombre}" required></div>
<div class="form-row"><div class="form-group"><label>Tipo</label><select class="form-control" name="tipo" required>
<option value="Personal" ${r.data.tipo==='Personal'?'selected':''}>Personal</option>
<option value="Material de Estudio" ${r.data.tipo==='Material de Estudio'?'selected':''}>Material de Estudio</option>
<option value="Tecnológico" ${r.data.tipo==='Tecnológico'?'selected':''}>Tecnológico</option></select></div>
<div class="form-group"><label>Fecha</label><input type="date" class="form-control" name="fecha" value="${r.data.fecha}" required></div></div>
<div class="form-row"><div class="form-group"><label>Hora</label><input type="time" class="form-control" name="hora" value="${r.data.hora}" required></div>
<div class="form-group"><label>Lugar</label><select class="form-control" name="lugar" required>${lugaresOptions(r.data.lugar)}</select></div></div>
<div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion" required>${r.data.descripcion}</textarea></div>
<div class="form-group"><label>Imagen</label><input type="file" class="form-control" name="imagen" accept="image/*"></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> Actualizar</button></form>`);
$('#oF').on('submit',async function(e){e.preventDefault();const fd=new FormData(this);fd.append('_method','PUT');
try{await API.upload(`/admin/objetos/${id}`,fd);showAlert('success','Actualizado');closeModal();load(cp);}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});
}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

async function del(id){if(!confirm('¿Eliminar?'))return;try{await API.delete(`/admin/objetos/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()||!Auth.isAdmin()){window.location.href='/dashboard';return;}load();
$('#fTipo').on('change',()=>load(1));let t;$('#sO').on('input',function(){clearTimeout(t);t=setTimeout(()=>load(1),500);});});
</script>
@endpush
