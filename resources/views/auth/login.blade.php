@extends('layouts.app')
@section('title', 'Iniciar Sesión — Looking For')
@section('nav-login', 'active')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__logo">
            <img src="{{ asset('img/logo.png') }}" alt="Looking For">
        </div>
        <h1 class="auth-card__title">Bienvenido de vuelta</h1>
        <p class="auth-card__subtitle">Inicia sesión para acceder a tu cuenta</p>

        <form id="loginForm">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="tu@correo.com" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="••••••" required>
            </div>
            <button type="submit" class="btn btn--primary btn--block btn--lg" id="btnLogin">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>

        <div class="auth-card__footer">
            ¿No tienes cuenta? <a href="{{ url('/register') }}">Regístrate aquí</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if (Auth.isLoggedIn()) window.location.href = '/dashboard';

    $('#loginForm').on('submit', async function(e) {
        e.preventDefault();
        $('.form-error').remove();
        const btn = $('#btnLogin');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Ingresando...');

        try {
            const res = await API.post('/login', {
                email: $('#email').val(),
                password: $('#password').val()
            });
            Auth.setToken(res.data.token);
            Auth.setUser(res.data.user);
            showAlert('success', `¡Bienvenido, ${res.data.user.nombre}!`);
            setTimeout(() => window.location.href = '/dashboard', 800);
        } catch (err) {
            showAlert('error', err.message || 'Credenciales incorrectas.');
            if (err.errors) showErrors(err.errors);
            btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Iniciar Sesión');
        }
    });
});
</script>
@endpush
