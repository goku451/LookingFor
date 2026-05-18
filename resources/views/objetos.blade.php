@extends('layouts.app')
@section('title', 'Objetos Perdidos — Looking For')
@section('nav-objetos', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title"><i class="fas fa-box-open"></i> Mis Objetos Perdidos</h1>
    </div>
    <div class="section__toolbar">
        <button class="btn btn--primary" id="btnNuevoObjeto"><i class="fas fa-plus"></i> Nuevo</button>
        <div class="search-bar"><i class="fas fa-search"></i><input type="text" id="searchObjetos" placeholder="Buscar..."></div>
    </div>
    <div class="grid grid--3" id="objetosList"></div>
    <div id="objetosPag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();try{const r=await API.get(`/objetos?page=${p}`);
if(!r.data.length){$('#objetosList').html('<div class="empty-state"><i class="fas fa-box-open"></i><p>Sin objetos aún.</p></div>');$('#objetosPag').html('');return;}
let h='';r.data.forEach(o=>{h+=`<div class="card">${o.imagen?`<img src="${o.imagen}" class="card__img">`:''}
<div class="card__header"><span class="card__title">${o.nombre}</span><span class="card__badge ${badgeClass(o.tipo)}">${o.tipo}</span></div>
<p style="color:var(--text-secondary);font-size:.9rem">${(o.descripcion||'').substring(0,100)}...</p>
<div class="card__meta"><span><i class="fas fa-map-marker-alt"></i> ${o.lugar}</span><span><i class="fas fa-calendar"></i> ${formatDate(o.fecha)}</span></div>
<div class="card__actions"><button class="btn btn--outline btn--sm" onclick="edit(${o.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${o.id})"><i class="fas fa-trash"></i></button></div></div>`;});
$('#objetosList').html(h);$('#objetosPag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

function form(o=null){const t=o?'Editar Objeto':'Nuevo Objeto';openModal(t,`<form id="oF">
<div class="form-group"><label>Nombre</label><input class="form-control" name="nombre" value="${o?.nombre||''}" required></div>
<div class="form-row"><div class="form-group"><label>Tipo</label><select class="form-control" name="tipo" required>
<option value="Personal" ${o?.tipo==='Personal'?'selected':''}>Personal</option>
<option value="Material de Estudio" ${o?.tipo==='Material de Estudio'?'selected':''}>Material de Estudio</option>
<option value="Tecnológico" ${o?.tipo==='Tecnológico'?'selected':''}>Tecnológico</option></select></div>
<div class="form-group"><label>Fecha</label><input type="date" class="form-control" name="fecha" value="${o?.fecha||''}" required></div></div>
<div class="form-row"><div class="form-group"><label>Hora</label><input type="time" class="form-control" name="hora" value="${o?.hora||''}" required></div>
<div class="form-group"><label>Lugar</label><select class="form-control" name="lugar" required>${lugaresOptions(o?.lugar)}</select></div></div>
<div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion" required>${o?.descripcion||''}</textarea></div>
<div class="form-group"><label>Imagen</label><input type="file" class="form-control" name="imagen" accept="image/*"></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> ${o?'Actualizar':'Registrar'}</button></form>`);
$('#oF').on('submit',async function(e){e.preventDefault();const fd=new FormData(this);try{
if(o){fd.append('_method','PUT');await API.upload(`/objetos/${o.id}`,fd);showAlert('success','Actualizado');}
else{await API.upload('/objetos',fd);showAlert('success','Registrado');}closeModal();load(cp);
}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});}

async function edit(id){showLoading();try{const r=await API.get(`/objetos/${id}`);form(r.data);}catch(e){showAlert('error',e.message);}finally{hideLoading();}}
async function del(id){if(!confirm('¿Eliminar?'))return;try{await API.delete(`/objetos/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()){window.location.href='/login';return;}load();
$('#btnNuevoObjeto').on('click',()=>form());let t;$('#searchObjetos').on('input',function(){clearTimeout(t);t=setTimeout(()=>load(1),500);});});
</script>
@endpush
