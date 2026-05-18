@extends('layouts.app')
@section('title', 'Servicios — Looking For')

@section('content')
{{-- HERO --}}
<section class="hero" style="min-height:50vh">
    <div class="hero__bg"></div>
    <div class="hero__content">
        <h1 class="hero__title">Nuestros Servicios</h1>
        <p class="hero__subtitle">Cuidando de tus pertenencias como si fueran nuestras</p>
    </div>
</section>

{{-- SERVICIOS --}}
<section class="section">
    <div class="features">
        <div class="feature">
            <div class="feature__icon" style="color:var(--accent)"><i class="fas fa-clipboard-list"></i></div>
            <h3>Registro de Objetos Perdidos</h3>
            <p>Puedes reportar la pérdida de objetos para habilitar la publicación y facilitar la recuperación de tu objeto extraviado.</p>
        </div>
        <div class="feature">
            <div class="feature__icon" style="color:var(--cyan)"><i class="fas fa-search"></i></div>
            <h3>Búsqueda de Objetos Perdidos</h3>
            <p>Las publicaciones informan a los miembros de la institución y facilitan que tu objeto pueda llegar a coordinación lo antes posible.</p>
        </div>
        <div class="feature">
            <div class="feature__icon" style="color:var(--success)"><i class="fas fa-hand-holding-heart"></i></div>
            <h3>Reclamación de Objetos</h3>
            <p>Podrás ver tu objeto perdido mediante publicaciones y podrás ir a reclamarlo en coordinación para recuperar tu tranquilidad.</p>
        </div>
    </div>
</section>

{{-- CONSEJOS CARRUSEL --}}
<section class="section" style="text-align:center">
    <div class="section__header" style="margin-bottom:2rem">
        <h2 class="section__title">¿Cómo cuidar mejor mis pertenencias?</h2>
        <p class="section__subtitle">Consejos útiles para evitar pérdidas</p>
    </div>
    <div class="card" style="max-width:700px;margin:0 auto;position:relative;min-height:150px">
        <div id="carruselContent" style="font-size:1.1rem;color:var(--text-secondary);line-height:1.8;padding:1rem">
        </div>
        <div style="display:flex;justify-content:center;gap:1rem;margin-top:1.5rem">
            <button class="btn btn--outline btn--sm" id="prevConsejo"><i class="fas fa-chevron-left"></i></button>
            <span id="carruselIndicator" style="color:var(--text-muted);font-size:.85rem;align-self:center"></span>
            <button class="btn btn--outline btn--sm" id="nextConsejo"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>

{{-- CATEGORÍAS --}}
<section class="section">
    <div class="section__header" style="text-align:center;margin-bottom:2rem">
        <h2 class="section__title">Categorías de Objetos</h2>
    </div>
    <div class="grid grid--3">
        <div class="card" style="text-align:center">
            <div style="font-size:2.5rem;color:var(--accent);margin-bottom:1rem"><i class="fas fa-user-tie"></i></div>
            <h3 class="card__title">Objetos Personales</h3>
            <p style="color:var(--text-muted);font-size:.9rem">Ropa, accesorios, objetos de valor...</p>
        </div>
        <div class="card" style="text-align:center">
            <div style="font-size:2.5rem;color:var(--cyan);margin-bottom:1rem"><i class="fas fa-book"></i></div>
            <h3 class="card__title">Material de Estudio</h3>
            <p style="color:var(--text-muted);font-size:.9rem">Libros, cuadernos, lápices...</p>
        </div>
        <div class="card" style="text-align:center">
            <div style="font-size:2.5rem;color:var(--success);margin-bottom:1rem"><i class="fas fa-laptop"></i></div>
            <h3 class="card__title">Objetos Tecnológicos</h3>
            <p style="color:var(--text-muted);font-size:.9rem">Teléfonos, computadoras, audífonos...</p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
const consejos = [
    "Marca tus pertenencias con tu nombre y número de contacto para facilitar su devolución en caso de pérdida.",
    "Guarda tus objetos valiosos en lugares seguros y evita dejarlos desatendidos.",
    "Usa bolsos y mochilas con cierres seguros para evitar que se te caigan cosas.",
    "No dejes objetos de mucho valor en tu aula o en lugares no seguros sin supervisión.",
    "Lleva contigo solo lo esencial para reducir el riesgo de pérdida.",
    "Haz copias de seguridad de documentos importantes y guarda copias físicas en lugares seguros.",
    "Si usas dispositivos electrónicos, habilita funciones de localización y bloqueo en caso de pérdida o robo.",
    "Mantén un registro de tus pertenencias más valiosas y revisa periódicamente que todo esté en su lugar.",
    "Evita compartir información personal sensible o la ubicación de tus pertenencias con los demás."
];
let ci=0;
function showConsejo(){$('#carruselContent').html(`<i class="fas fa-lightbulb" style="color:var(--warning);font-size:1.5rem;display:block;margin-bottom:.5rem"></i>${consejos[ci]}`);
$('#carruselIndicator').text(`${ci+1} / ${consejos.length}`);}
$('#prevConsejo').on('click',()=>{ci=(ci-1+consejos.length)%consejos.length;showConsejo();});
$('#nextConsejo').on('click',()=>{ci=(ci+1)%consejos.length;showConsejo();});
showConsejo();setInterval(()=>{ci=(ci+1)%consejos.length;showConsejo();},6000);
</script>
@endpush
