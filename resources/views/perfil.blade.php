@extends('layouts.app')
@section('title', 'Mi Perfil — Looking For')

@section('content')
<section class="section">
    <div class="profile-header" id="profileHeader">
        <img class="profile-avatar" id="profileAvatar" src="" alt="Foto">
        <div class="profile-info">
            <h2 id="profileNombre">Cargando...</h2>
            <p id="profileEmail"></p>
            <span class="badge" id="profileRole"></span>
        </div>
    </div>

    <div class="grid grid--2">
        <div class="card">
            <h3 class="card__title" style="margin-bottom:1rem"><i class="fas fa-user-edit"></i> Editar Perfil</h3>
            <form id="perfilForm">
                <div class="form-group"><label>Nombre</label><input class="form-control" name="nombre" id="pNombre"></div>
                <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="pEmail"></div>
                <div class="form-row">
                    <div class="form-group"><label>Código</label><input class="form-control" name="codigo" id="pCodigo"></div>
                    <div class="form-group"><label>Teléfono</label><input class="form-control" name="telefono" id="pTelefono"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Nivel</label><input class="form-control" name="nivel" id="pNivel"></div>
                    <div class="form-group"><label>Grado</label><input class="form-control" name="grado" id="pGrado"></div>
                </div>
                <div class="form-group"><label>Sección</label><input class="form-control" name="seccion" id="pSeccion"></div>
                <div class="form-group"><label>Foto</label><input type="file" class="form-control" name="foto" accept="image/*"></div>
                <button type="submit" class="btn btn--primary btn--block"><i class="fas fa-save"></i> Guardar</button>
            </form>
        </div>
        <div class="card">
            <h3 class="card__title" style="margin-bottom:1rem"><i class="fas fa-lock"></i> Cambiar Contraseña</h3>
            <form id="passwordForm">
                <div class="form-group"><label>Contraseña Actual</label><input type="password" class="form-control" name="password_actual" required></div>
                <div class="form-group"><label>Nueva Contraseña</label><input type="password" class="form-control" name="password" required></div>
                <div class="form-group"><label>Confirmar</label><input type="password" class="form-control" name="password_confirmation" required></div>
                <button type="submit" class="btn btn--warning btn--block"><i class="fas fa-key"></i> Cambiar</button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
async function loadProfile(){showLoading();try{const r=await API.get('/perfil');const u=r.data;
$('#profileAvatar').attr('src',u.foto||'https://ui-avatars.com/api/?name='+encodeURIComponent(u.nombre)+'&background=7c3aed&color=fff&size=100');
$('#profileNombre').text(u.nombre);$('#profileEmail').text(u.email);$('#profileRole').text(u.role?.nombre||'');
$('#pNombre').val(u.nombre);$('#pEmail').val(u.email);$('#pCodigo').val(u.codigo);$('#pTelefono').val(u.telefono);
$('#pNivel').val(u.nivel);$('#pGrado').val(u.grado);$('#pSeccion').val(u.seccion);
}catch(e){showAlert('error',e.message);}finally{hideLoading();}}

$(document).ready(function(){if(!Auth.isLoggedIn()){window.location.href='/login';return;}loadProfile();
$('#perfilForm').on('submit',async function(e){e.preventDefault();$('.form-error').remove();const fd=new FormData(this);try{
await API.upload('/perfil',fd);showAlert('success','Perfil actualizado');loadProfile();}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});
$('#passwordForm').on('submit',async function(e){e.preventDefault();$('.form-error').remove();const d={};$(this).serializeArray().forEach(f=>{d[f.name]=f.value;});try{
const r=await API.put('/perfil/password',d);Auth.setToken(r.data.token);showAlert('success','Contraseña actualizada');this.reset();}catch(er){showAlert('error',er.message);if(er.errors)showErrors(er.errors);}});
});
</script>
@endpush
