@extends('layouts.app')
@section('title', 'Admin Dashboard — Looking For')
@section('nav-admin', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title"><i class="fas fa-chart-bar"></i> Panel de Administración</h1>
        <p class="section__subtitle">Estadísticas generales del sistema</p>
    </div>
    <div class="stats" id="adminStats"></div>

    <div class="grid grid--2" style="margin-top:2rem">
        <div class="card">
            <h3 class="card__title"><i class="fas fa-box"></i> Objetos por Tipo</h3>
            <div id="objTipo" style="margin-top:1rem"></div>
        </div>
        <div class="card">
            <h3 class="card__title"><i class="fas fa-file-alt"></i> Reportes por Tipo</h3>
            <div id="repTipo" style="margin-top:1rem"></div>
        </div>
    </div>

    <div class="card" style="margin-top:1.5rem">
        <h3 class="card__title"><i class="fas fa-clock"></i> Actividad Últimos 7 Días</h3>
        <div class="stats" id="recentStats" style="margin-top:1rem"></div>
    </div>

    <div class="grid grid--2" style="margin-top:1.5rem">
        <a href="{{ url('/admin/usuarios') }}" class="card" style="text-decoration:none;color:inherit"><div style="font-size:2rem;color:var(--accent);margin-bottom:.5rem"><i class="fas fa-users"></i></div><h3>Gestionar Usuarios</h3></a>
        <a href="{{ url('/admin/objetos') }}" class="card" style="text-decoration:none;color:inherit"><div style="font-size:2rem;color:var(--cyan);margin-bottom:.5rem"><i class="fas fa-box-open"></i></div><h3>Gestionar Objetos</h3></a>
        <a href="{{ url('/admin/reportes') }}" class="card" style="text-decoration:none;color:inherit"><div style="font-size:2rem;color:var(--success);margin-bottom:.5rem"><i class="fas fa-clipboard-list"></i></div><h3>Gestionar Reportes</h3></a>
        <a href="{{ url('/admin/publicaciones') }}" class="card" style="text-decoration:none;color:inherit"><div style="font-size:2rem;color:var(--warning);margin-bottom:.5rem"><i class="fas fa-comments"></i></div><h3>Gestionar Publicaciones</h3></a>
    </div>
</section>
@endsection

@push('scripts')
<script>
function bar(label,val,max,color){const pct=max?Math.round(val/max*100):0;
return `<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem"><span style="min-width:140px;font-size:.85rem;color:var(--text-secondary)">${label}</span>
<div style="flex:1;height:24px;background:var(--bg-input);border-radius:4px;overflow:hidden"><div style="height:100%;width:${pct}%;background:${color};border-radius:4px;transition:.5s"></div></div>
<span style="font-weight:700;min-width:30px">${val}</span></div>`;}

$(document).ready(async function(){if(!Auth.isLoggedIn()||!Auth.isAdmin()){window.location.href='/dashboard';return;}
showLoading();try{const r=await API.get('/admin/dashboard');const d=r.data;const c=d.conteos;
$('#adminStats').html(`
<div class="stat"><div class="stat__icon stat__icon--purple"><i class="fas fa-users"></i></div><div class="stat__value">${c.usuarios}</div><div class="stat__label">Usuarios</div></div>
<div class="stat"><div class="stat__icon stat__icon--cyan"><i class="fas fa-box-open"></i></div><div class="stat__value">${c.objetos}</div><div class="stat__label">Objetos</div></div>
<div class="stat"><div class="stat__icon stat__icon--green"><i class="fas fa-clipboard-list"></i></div><div class="stat__value">${c.reportes}</div><div class="stat__label">Reportes</div></div>
<div class="stat"><div class="stat__icon stat__icon--yellow"><i class="fas fa-comments"></i></div><div class="stat__value">${c.publicaciones}</div><div class="stat__label">Publicaciones</div></div>`);

const ot=d.objetos_por_tipo||{};const maxO=Math.max(...Object.values(ot),1);
$('#objTipo').html(bar('Personal',ot['Personal']||0,maxO,'var(--accent)')+bar('Material de Estudio',ot['Material de Estudio']||0,maxO,'var(--cyan)')+bar('Tecnológico',ot['Tecnológico']||0,maxO,'var(--success)'));

const rt=d.reportes_por_tipo||{};const maxR=Math.max(...Object.values(rt),1);
$('#repTipo').html(bar('Personal',rt['Personal']||0,maxR,'var(--accent)')+bar('Material de Estudio',rt['Material de Estudio']||0,maxR,'var(--cyan)')+bar('Tecnológico',rt['Tecnológico']||0,maxR,'var(--success)'));

const rc=d.recientes_7_dias||{};
$('#recentStats').html(`<div class="stat"><div class="stat__value" style="color:var(--cyan)">${rc.objetos||0}</div><div class="stat__label">Objetos Nuevos</div></div>
<div class="stat"><div class="stat__value" style="color:var(--success)">${rc.reportes||0}</div><div class="stat__label">Reportes Nuevos</div></div>
<div class="stat"><div class="stat__value" style="color:var(--accent)">${rc.usuarios||0}</div><div class="stat__label">Usuarios Nuevos</div></div>`);
}catch(e){showAlert('error',e.message);}finally{hideLoading();}});
</script>
@endpush
