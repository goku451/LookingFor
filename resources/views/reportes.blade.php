@extends('layouts.app')
@section('title', 'Reportes — Looking For')
@section('nav-reportes', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title"><i class="fas fa-clipboard-list"></i> Mis Reportes</h1>
    </div>
    <div class="section__toolbar">
        <button class="btn btn--primary" id="btnNuevoReporte"><i class="fas fa-plus"></i> Nuevo Reporte</button>
        <div class="search-bar"><i class="fas fa-search"></i><input type="text" id="searchR" placeholder="Buscar..."></div>
    </div>
    <div class="grid grid--2" id="reportesList"></div>
    <div id="reportesPag"></div>
</section>
@endsection

@push('scripts')
<script>
let cp=1;
async function load(p=1){cp=p;showLoading();try{const r=await API.get(`/reportes?page=${p}`);
if(!r.data.length){$('#reportesList').html('<div class="empty-state"><i class="fas fa-clipboard"></i><p>Sin reportes aún.</p></div>');$('#reportesPag').html('');return;}
let h='';r.data.forEach(o=>{h+=`<div class="card">${o.imagen?`<img src="${o.imagen}" class="card__img">`:''}
<div class="card__header"><span class="card__title">${o.nombre_objeto}</span><span class="card__badge ${badgeClass(o.tipo)}">${o.tipo}</span></div>
<p style="color:var(--text-secondary);font-size:.85rem"><strong>Reportante:</strong> ${o.nombre_reportante}</p>
<p style="color:var(--text-secondary);font-size:.85rem">${(o.descripcion||'').substring(0,100)}...</p>
<div class="card__meta"><span><i class="fas fa-map-marker-alt"></i> ${o.lugar}</span><span><i class="fas fa-calendar"></i> ${formatDate(o.fecha)}</span></div>
<div class="card__actions"><button class="btn btn--outline btn--sm" onclick="edit(${o.id})"><i class="fas fa-edit"></i></button>
<button class="btn btn--danger btn--sm" onclick="del(${o.id})"><i class="fas fa-trash"></i></button></div></div>`;});
$('#reportesList').html(h);$('#reportesPag').html(renderPagination(r.meta,'load'));}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

function form(o=null){const t=o?'Editar Reporte':'Nuevo Reporte';openModal(t,`<form id="rF" style="max-height:60vh;overflow-y:auto">
<div class="form-row"><div class="form-group"><label>Nombre Reportante</label><input class="form-control" name="nombre_reportante" value="${o?.nombre_reportante||''}" required></div>
<div class="form-group"><label>Código</label><input class="form-control" name="codigo_reportante" value="${o?.codigo_reportante||''}"></div></div>
<div class="form-row"><div class="form-group"><label>Correo</label><input type="email" class="form-control" name="correo" value="${o?.correo||''}"></div>
<div class="form-group"><label>Teléfono</label><input class="form-control" name="telefono" value="${o?.telefono||''}"></div></div>
<div class="form-row"><div class="form-group"><label>Nivel</label><input class="form-control" name="nivel" value="${o?.nivel||''}"></div>
<div class="form-group"><label>Grado</label><input class="form-control" name="grado" value="${o?.grado||''}"></div></div>
<div class="form-group"><label>Sección</label><input class="form-control" name="seccion" value="${o?.seccion||''}"></div>
<hr style="border-color:var(--border);margin:1rem 0"><h3 style="font-size:1rem;margin-bottom:1rem">Datos del Objeto</h3>
<div class="form-row"><div class="form-group"><label>Nombre Objeto</label><input class="form-control" name="nombre_objeto" value="${o?.nombre_objeto||''}" required></div>
<div class="form-group"><label>Tipo</label><select class="form-control" name="tipo" required>
<option value="Personal" ${o?.tipo==='Personal'?'selected':''}>Personal</option>
<option value="Material de Estudio" ${o?.tipo==='Material de Estudio'?'selected':''}>Material de Estudio</option>
<option value="Tecnológico" ${o?.tipo==='Tecnológico'?'selected':''}>Tecnológico</option></select></div></div>
<div class="form-row"><div class="form-group"><label>Fecha</label><input type="date" class="form-control" name="fecha" value="${o?.fecha||''}" required></div>
<div class="form-group"><label>Hora</label><input type="time" class="form-control" name="hora" value="${o?.hora||''}" required></div></div>
<div class="form-group"><label>Lugar</label><select class="form-control" name="lugar" required>${lugaresOptions(o?.lugar)}</select></div>
<div class="form-group"><label>Descripción</label><textarea class="form-control" name="descripcion" required>${o?.descripcion||''}</textarea></div>
<div class="form-group"><label>Imagen</label><input type="file" class="form-control" name="imagen" accept="image/*"></div>
<button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> ${o?'Actualizar':'Registrar'}</button></form>`);
$('#rF').on('submit',async function(e){e.preventDefault();const fd=new FormData(this);try{
if(o){fd.append('_method','PUT');await API.upload(`/reportes/${o.id}`,fd);showAlert('success','Actualizado');}
else{await API.upload('/reportes',fd);showAlert('success','Registrado');}closeModal();load(cp);
}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});}

async function edit(id){showLoading();try{const r=await API.get(`/reportes/${id}`);form(r.data);}catch(e){showAlert('error',e.message);}finally{hideLoading();}}
async function del(id){if(!confirm('¿Eliminar?'))return;try{await API.delete(`/reportes/${id}`);showAlert('success','Eliminado');load(cp);}catch(e){showAlert('error',e.message);}}

$(document).ready(function(){if(!Auth.isLoggedIn()){window.location.href='/login';return;}load();
$('#btnNuevoReporte').on('click',()=>form());});
</script>
@endpush
