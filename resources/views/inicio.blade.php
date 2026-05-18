@extends('layouts.app')
@section('title', 'Looking For — Encuentra lo perdido')
@section('nav-inicio', 'active')

@section('content')
{{-- ═══ HERO ═══ --}}
<section class="hero">
    <div class="hero__bg"></div>
    <div class="hero__content">
        <span class="hero__badge">🔍 Plataforma de Objetos Perdidos</span>
        <h1 class="hero__title">Encuentra lo perdido, recupera tu tranquilidad</h1>
        <p class="hero__subtitle">
            Te ayudamos a recuperar tus pertenencias de manera segura y confiable.
            Registra, busca y encuentra objetos dentro de tu institución educativa.
        </p>
        <div class="hero__actions">
            <a href="{{ url('/login') }}" class="btn btn--primary btn--lg">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
            <a href="{{ url('/register') }}" class="btn btn--outline btn--lg">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
        </div>
    </div>
</section>

{{-- ═══ FEATURES ═══ --}}
<section class="section">
    <div class="section__header" style="text-align:center; margin-bottom: 3rem;">
        <h2 class="section__title">¿Cómo funciona?</h2>
        <p class="section__subtitle">Tres pasos sencillos para recuperar tus pertenencias</p>
    </div>
    <div class="features">
        <div class="feature">
            <div class="feature__icon" style="color: var(--accent);">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>Registra tu objeto</h3>
            <p>Describe el objeto perdido con fotos, ubicación y detalles para que otros puedan ayudarte a encontrarlo.</p>
        </div>
        <div class="feature">
            <div class="feature__icon" style="color: var(--cyan);">
                <i class="fas fa-search"></i>
            </div>
            <h3>Busca y compara</h3>
            <p>Revisa los reportes de objetos encontrados y filtra por categoría, fecha o lugar para encontrar coincidencias.</p>
        </div>
        <div class="feature">
            <div class="feature__icon" style="color: var(--success);">
                <i class="fas fa-handshake"></i>
            </div>
            <h3>Recupera tu objeto</h3>
            <p>Contacta al reportante y recupera tus pertenencias de forma segura en coordinación de la institución.</p>
        </div>
    </div>
</section>

{{-- ═══ MISIÓN / VISIÓN ═══ --}}
<section class="section">
    <div class="section__header" style="text-align:center; margin-bottom: 3rem;">
        <h2 class="section__title">Sobre Nosotros</h2>
        <p class="section__subtitle">Conoce nuestra misión y visión</p>
    </div>
    <div class="grid grid--3">
        <div class="card" style="text-align:center;">
            <div style="font-size:2rem; color:var(--accent); margin-bottom:1rem;">
                <i class="fas fa-bullseye"></i>
            </div>
            <h3 class="card__title">Misión</h3>
            <p style="color:var(--text-secondary); font-size:.9rem; margin-top:.5rem;">
                Proporcionar una plataforma eficiente y segura para la gestión de objetos perdidos y encontrados,
                facilitando la recuperación de pertenencias personales de nuestros estudiantes, profesores y personal.
            </p>
        </div>
        <div class="card" style="text-align:center;">
            <div style="font-size:2rem; color:var(--cyan); margin-bottom:1rem;">
                <i class="fas fa-eye"></i>
            </div>
            <h3 class="card__title">Visión</h3>
            <p style="color:var(--text-secondary); font-size:.9rem; margin-top:.5rem;">
                Convertirnos en el referente principal en la gestión de objetos perdidos y encontrados
                en instituciones educativas, destacándonos por eficacia y accesibilidad.
            </p>
        </div>
        <div class="card" style="text-align:center;">
            <div style="font-size:2rem; color:var(--success); margin-bottom:1rem;">
                <i class="fas fa-heart"></i>
            </div>
            <h3 class="card__title">Valores</h3>
            <p style="color:var(--text-secondary); font-size:.9rem; margin-top:.5rem;">
                Responsabilidad, eficiencia, colaboración, seguridad, innovación y empatía.
                Tratamos cada caso con máxima consideración y atención.
            </p>
        </div>
    </div>
</section>
@endsection
