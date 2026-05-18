@extends('layouts.app')
@section('title', 'Dashboard — Looking For')
@section('nav-dashboard', 'active')

@section('content')
<section class="section">
    <div class="section__header">
        <h1 class="section__title" id="welcomeTitle">Bienvenido</h1>
        <p class="section__subtitle">Aquí tienes un resumen de tu actividad</p>
    </div>

    <div class="stats" id="userStats">
        <div class="stat">
            <div class="stat__icon stat__icon--purple"><i class="fas fa-box-open"></i></div>
            <div class="stat__value" id="statObjetos">—</div>
            <div class="stat__label">Objetos Perdidos</div>
        </div>
        <div class="stat">
            <div class="stat__icon stat__icon--cyan"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat__value" id="statReportes">—</div>
            <div class="stat__label">Reportes</div>
        </div>
        <div class="stat">
            <div class="stat__icon stat__icon--green"><i class="fas fa-comments"></i></div>
            <div class="stat__value" id="statPublicaciones">—</div>
            <div class="stat__label">Publicaciones</div>
        </div>
    </div>

    <div class="grid grid--3">
        <a href="{{ url('/objetos') }}" class="card" style="text-decoration:none; color:inherit;">
            <div style="font-size:2rem; color:var(--accent); margin-bottom:1rem;">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="card__title">Registrar Objeto Perdido</h3>
            <p style="color:var(--text-muted); font-size:.9rem;">
                Reporta un objeto que hayas extraviado con descripción, foto y ubicación.
            </p>
        </a>
        <a href="{{ url('/reportes') }}" class="card" style="text-decoration:none; color:inherit;">
            <div style="font-size:2rem; color:var(--cyan); margin-bottom:1rem;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="card__title">Reportar Objeto Encontrado</h3>
            <p style="color:var(--text-muted); font-size:.9rem;">
                ¿Encontraste algo? Repórtalo para que su dueño pueda reclamarlo.
            </p>
        </a>
        <a href="{{ url('/publicaciones') }}" class="card" style="text-decoration:none; color:inherit;">
            <div style="font-size:2rem; color:var(--success); margin-bottom:1rem;">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="card__title">Publicaciones</h3>
            <p style="color:var(--text-muted); font-size:.9rem;">
                Comparte información y ayuda a la comunidad a encontrar sus pertenencias.
            </p>
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(async function() {
    if (!Auth.isLoggedIn()) { window.location.href = '/login'; return; }

    const user = Auth.getUser();
    if (user) $('#welcomeTitle').text(`Bienvenido, ${user.nombre}`);

    try {
        const [obj, rep, pub] = await Promise.all([
            API.get('/objetos?per_page=1'),
            API.get('/reportes?per_page=1'),
            API.get('/publicaciones?per_page=1')
        ]);
        $('#statObjetos').text(obj.meta?.total ?? 0);
        $('#statReportes').text(rep.meta?.total ?? 0);
        $('#statPublicaciones').text(pub.meta?.total ?? 0);
    } catch {}
});
</script>
@endpush
