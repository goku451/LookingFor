@extends('layouts.app')
@section('title', 'Registrarse — Looking For')
@section('nav-register', 'active')

@section('content')
<div class="auth-page">
    <div class="auth-card" style="max-width: 520px;">
        <div class="auth-card__logo">
            <img src="{{ asset('img/logo.png') }}" alt="Looking For">
        </div>
        <h1 class="auth-card__title">Crear Cuenta</h1>
        <p class="auth-card__subtitle">Regístrate como alumno para empezar</p>

        <form id="registerForm">
            <div class="form-group">
                <label for="nombre"><i class="fas fa-user"></i> Nombre Completo</label>
                <input type="text" class="form-control" name="nombre" placeholder="Tu nombre completo" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="codigo"><i class="fas fa-id-badge"></i> Código</label>
                    <input type="text" class="form-control" name="codigo" placeholder="ALU0001">
                </div>
                <div class="form-group">
                    <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                    <input type="text" class="form-control" name="telefono" placeholder="7890-1234">
                </div>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="tu@correo.com" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" class="form-control" name="password" placeholder="Mín. 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar</label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Repetir" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="nivel">Nivel</label>
                    <select class="form-control" name="nivel">
                        <option value="">Seleccionar</option>
                        <option>Básica</option><option>Media</option><option>Superior</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="grado">Grado</label>
                    <input type="text" class="form-control" name="grado" placeholder="Ej: 1°">
                </div>
            </div>
            <div class="form-group">
                <label for="seccion">Sección</label>
                <input type="text" class="form-control" name="seccion" placeholder="Ej: A">
            </div>

            <button type="submit" class="btn btn--primary btn--block btn--lg" id="btnRegister">
                <i class="fas fa-user-plus"></i> Registrarse
            </button>
        </form>

        <div class="auth-card__footer">
            ¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if (Auth.isLoggedIn()) window.location.href = '/dashboard';

    $('#registerForm').on('submit', async function(e) {
        e.preventDefault();
        $('.form-error').remove();
        const btn = $('#btnRegister');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Registrando...');

        const data = {};
        $(this).serializeArray().forEach(f => { if (f.value) data[f.name] = f.value; });

        try {
            const res = await API.post('/register', data);
            Auth.setToken(res.data.token);
            Auth.setUser(res.data.user);
            showAlert('success', '¡Cuenta creada exitosamente!');
            setTimeout(() => window.location.href = '/dashboard', 800);
        } catch (err) {
            let errorMsg = err.message || 'Error al registrar.';
            if (err.debug && err.debug.message) {
                errorMsg += ' | ' + err.debug.message;
            }
            showAlert('error', errorMsg);
            if (err.errors) showErrors(err.errors);
            btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Registrarse');
        }
    });
});
</script>
@endpush
