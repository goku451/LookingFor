@extends('layouts.app')
@section('title', 'Publicaciones — Looking For')
@section('nav-publicaciones', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title"><i class="fas fa-comments"></i> Mis Publicaciones</h1>
    </div>
    <div class="section__toolbar">
        <button class="btn btn--primary" id="btnNuevaPub"><i class="fas fa-plus"></i> Nueva Publicación</button>
    </div>
    <div id="pubList"></div>
    <div id="pubPag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();try{const r=await API.get(`/publicaciones?page=${p}`);
if(!r.data.length){$('#pubList').html('<div class="empty-state"><i class="fas fa-comments"></i><p>Sin publicaciones aún.</p></div>');return;}
let h='';r.data.forEach(o=>{h+=`<div class="card" style="margin-bottom:1rem">
<div class="card__header"><span class="card__title"><i class="fas fa-calendar"></i> ${formatDate(o.fecha)}</span></div>
<p style="color:var(--text-secondary);line-height:1.6">${o.comentarios}</p>
<div class="card__actions"><button class="btn btn--outline btn--sm" onclick="edit(${o.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${o.id})"><i class="fas fa-trash"></i></button></div></div>`;});
$('#pubList').html(h);$('#pubPag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

function form(o=null){openModal(o?'Editar':'Nueva Publicación',`<form id="pF">
<div class="form-group"><label>Fecha</label><input type="date" class="form-control" name="fecha" value="${o?.fecha||new Date().toISOString().split('T')[0]}" required></div>
<div class="form-group"><label>Comentarios</label><textarea class="form-control" name="comentarios" rows="5" required>${o?.comentarios||''}</textarea></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> ${o?'Actualizar':'Publicar'}</button></form>`);
$('#pF').on('submit',async function(e){e.preventDefault();const d={};$(this).serializeArray().forEach(f=>{d[f.name]=f.value;});try{
if(o){await API.put(`/publicaciones/${o.id}`,d);showAlert('success','Actualizado');}
else{await API.post('/publicaciones',d);showAlert('success','Publicado');}closeModal();load(cp);
}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});}

async function edit(id){showLoading();try{const r=await API.get(`/publicaciones/${id}`);form(r.data);}catch(e){showAlert('error',e.message);}finally{hideLoading();}}
async function del(id){if(!confirm('¿Eliminar?'))return;try{await API.delete(`/publicaciones/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()){window.location.href='/login';return;}load();$('#btnNuevaPub').on('click',()=>form());});
</script>
@endpush
