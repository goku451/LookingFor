@extends('layouts.app')
@section('title', 'Admin Publicaciones — Looking For')
@section('nav-admin', 'active')

@section('content')
<section class="section">
    <div class="section__header"><h1 class="section__title"><i class="fas fa-comments"></i> Gestión de Publicaciones</h1></div>
    <div class="section__toolbar">
        <div class="search-bar"><i class="fas fa-search"></i><input type="text" id="sP" placeholder="Buscar..."></div>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr>
        <th>Fecha</th><th>Comentarios</th><th>Usuario</th><th>Acciones</th>
    </tr></thead><tbody id="tBody"></tbody></table></div>
    <div id="pag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();let url=`/admin/publicaciones?page=${p}`;
const b=$('#sP').val();if(b)url+=`&buscar=${b}`;
try{const r=await API.get(url);let h='';r.data.forEach(o=>{h+=`<tr>
<td>${formatDate(o.fecha)}</td><td>${(o.comentarios||'').substring(0,80)}...</td><td>${o.user?.nombre||'—'}</td>
<td><button class="btn btn--outline btn--sm" onclick="edit(${o.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${o.id})"><i class="fas fa-trash"></i></button></td></tr>`;});
$('#tBody').html(h||'<tr><td colspan="4" style="text-align:center;padding:2rem">Sin datos</td></tr>');
$('#pag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

async function edit(id){showLoading();try{const r=await API.get(`/admin/publicaciones/${id}`);const o=r.data;
openModal('Editar Publicación',`<form id="pF">
<div class="form-group"><label>Fecha</label><input type="date" class="form-control" name="fecha" value="${o.fecha}" required></div>
<div class="form-group"><label>Comentarios</label><textarea class="form-control" name="comentarios" rows="5" required>${o.comentarios}</textarea></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> Actualizar</button></form>`);
$('#pF').on('submit',async function(e){e.preventDefault();const d={};$(this).serializeArray().forEach(f=>{d[f.name]=f.value;});
try{await API.put(`/admin/publicaciones/${id}`,d);showAlert('success','Actualizado');closeModal();load(cp);}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});
}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

async function del(id){if(!confirm('¿Eliminar?'))return;try{await API.delete(`/admin/publicaciones/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()||!Auth.isAdmin()){window.location.href='/dashboard';return;}load();
let t;$('#sP').on('input',function(){clearTimeout(t);t=setTimeout(()=>load(1),500);});});
</script>
@endpush
