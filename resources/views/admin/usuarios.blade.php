@extends('layouts.app')
@section('title', 'Admin Usuarios — Looking For')
@section('nav-admin', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title"><i class="fas fa-users"></i> Gestión de Usuarios</h1>
    </div>
    <div class="section__toolbar">
        <button class="btn btn--primary" id="btnNuevoUser"><i class="fas fa-user-plus"></i> Nuevo Usuario</button>
        <div class="search-bar"><i class="fas fa-search"></i><input type="text" id="searchU" placeholder="Buscar..."></div>
        <select class="form-control" id="filterRol" style="width:auto;max-width:180px">
            <option value="">Todos los roles</option>
            <option value="alumno">Alumnos</option>
            <option value="profesor">Profesores</option>
            <option value="administrador">Administradores</option>
        </select>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr>
        <th>Foto</th><th>Nombre</th><th>Email</th><th>Código</th><th>Rol</th><th>Acciones</th>
    </tr></thead><tbody id="usersBody"></tbody></table></div>
    <div id="usersPag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();const rol=$('#filterRol').val();const buscar=$('#searchU').val();
let url=`/admin/usuarios?page=${p}`;if(rol)url+=`&rol=${rol}`;if(buscar)url+=`&buscar=${buscar}`;
try{const r=await API.get(url);let h='';r.data.forEach(u=>{h+=`<tr>
<td><img src="${u.foto||'https://ui-avatars.com/api/?name='+encodeURIComponent(u.nombre)+'&size=40&background=7c3aed&color=fff'}"></td>
<td>${u.nombre}</td><td>${u.email}</td><td>${u.codigo||'—'}</td><td><span class="card__badge card__badge--personal">${u.role?.nombre||''}</span></td>
<td><button class="btn btn--outline btn--sm" onclick="edit(${u.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${u.id})"><i class="fas fa-trash"></i></button></td></tr>`;});
$('#usersBody').html(h||'<tr><td colspan="6" style="text-align:center;padding:2rem">Sin usuarios</td></tr>');
$('#usersPag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

function form(u=null){openModal(u?'Editar Usuario':'Nuevo Usuario',`<form id="uF">
<div class="form-group"><label>Nombre</label><input class="form-control" name="nombre" value="${u?.nombre||''}" required></div>
<div class="form-row"><div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" value="${u?.email||''}" required></div>
<div class="form-group"><label>Contraseña ${u?'(dejar vacío)':''}</label><input type="password" class="form-control" name="password" ${u?'':'required'}></div></div>
<div class="form-row"><div class="form-group"><label>Código</label><input class="form-control" name="codigo" value="${u?.codigo||''}"></div>
<div class="form-group"><label>Teléfono</label><input class="form-control" name="telefono" value="${u?.telefono||''}"></div></div>
<div class="form-row"><div class="form-group"><label>Nivel</label><input class="form-control" name="nivel" value="${u?.nivel||''}"></div>
<div class="form-group"><label>Grado</label><input class="form-control" name="grado" value="${u?.grado||''}"></div></div>
<div class="form-row"><div class="form-group"><label>Sección</label><input class="form-control" name="seccion" value="${u?.seccion||''}"></div>
<div class="form-group"><label>Rol</label><select class="form-control" name="role_id" id="roleSelect" required></select></div></div>
<div class="form-group"><label>Foto</label><input type="file" class="form-control" name="foto" accept="image/*"></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> ${u?'Actualizar':'Crear'}</button></form>`);
loadRoles(u);$('#uF').on('submit',async function(e){e.preventDefault();const fd=new FormData(this);try{
if(u){fd.append('_method','PUT');await API.upload(`/admin/usuarios/${u.id}`,fd);showAlert('success','Actualizado');}
else{await API.upload('/admin/usuarios',fd);showAlert('success','Creado');}closeModal();load(cp);
}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});}

async function loadRoles(u){try{const r=await API.get('/admin/roles');let h='';r.data.forEach(ro=>{
h+=`<option value="${ro.id}" ${u?.role?.id===ro.id?'selected':''}>${ro.nombre}</option>`;});$('#roleSelect').html(h);}catch{}}

async function edit(id){showLoading();try{const r=await API.get(`/admin/usuarios/${id}`);form(r.data);}catch(e){showAlert('error',e.message);}finally{hideLoading();}}
async function del(id){if(!confirm('¿Eliminar usuario?'))return;try{await API.delete(`/admin/usuarios/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()||!Auth.isAdmin()){window.location.href='/dashboard';return;}load();
$('#btnNuevoUser').on('click',()=>form());$('#filterRol').on('change',()=>load(1));
let t;$('#searchU').on('input',function(){clearTimeout(t);t=setTimeout(()=>load(1),500);});});
</script>
@endpush
