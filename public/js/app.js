/**
 * Looking For — Core JavaScript
 * Maneja: auth via Sanctum, AJAX helpers, UI components
 */

const API_BASE = '/api/v1';

// ══════════════════════════════════════
// AUTH MANAGER
// ══════════════════════════════════════
const Auth = {
    getToken() { return localStorage.getItem('auth_token'); },
    setToken(token) { localStorage.setItem('auth_token', token); },
    removeToken() { localStorage.removeItem('auth_token'); localStorage.removeItem('auth_user'); },
    getUser() { try { return JSON.parse(localStorage.getItem('auth_user')); } catch { return null; } },
    setUser(user) { localStorage.setItem('auth_user', JSON.stringify(user)); },
    isLoggedIn() { return !!this.getToken(); },
    isAdmin() { const u = this.getUser(); return u?.role?.slug === 'administrador'; },
};

// ══════════════════════════════════════
// AJAX HELPER
// ══════════════════════════════════════
function api(method, url, data = null, isFormData = false) {
    const headers = { 'Accept': 'application/json' };
    
    // Obtener y enviar el token CSRF para peticiones stateful de Sanctum
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }

    const token = Auth.getToken();
    if (token) headers['Authorization'] = `Bearer ${token}`;
    if (!isFormData) headers['Content-Type'] = 'application/json';

    const config = { method, url: API_BASE + url, headers, timeout: 30000 };
    if (data) config.data = isFormData ? data : JSON.stringify(data);

    // Usar jQuery AJAX con Promise
    return new Promise((resolve, reject) => {
        $.ajax({
            ...config,
            success: (response) => resolve(response),
            error: (xhr) => {
                if (xhr.status === 401) {
                    Auth.removeToken();
                    window.location.href = '/login';
                    return;
                }
                const err = xhr.responseJSON || { message: 'Error de conexión' };
                reject(err);
            }
        });
    });
}

// Shortcuts
const API = {
    get: (url) => api('GET', url),
    post: (url, data, isForm = false) => api('POST', url, data, isForm),
    put: (url, data, isForm = false) => api('PUT', url, data, isForm),
    delete: (url) => api('DELETE', url),
    upload: (url, formData) => api('POST', url, formData, true),
};

// ══════════════════════════════════════
// UI HELPERS
// ══════════════════════════════════════
function showAlert(type, message, duration = 4000) {
    const icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
    const alert = $(`
        <div class="alert alert--${type}">
            <i class="fas fa-${icons[type] || 'info-circle'}"></i>
            <span>${message}</span>
            <button class="alert__close" onclick="$(this).parent().remove()"><i class="fas fa-times"></i></button>
        </div>
    `);
    $('#alertsContainer').append(alert);
    setTimeout(() => alert.fadeOut(300, () => alert.remove()), duration);
}

function showLoading() { $('#loadingOverlay').addClass('show'); }
function hideLoading() { $('#loadingOverlay').removeClass('show'); }

function openModal(title, bodyHtml) {
    $('#modalTitle').text(title);
    $('#modalBody').html(bodyHtml);
    $('#globalModal').addClass('show');
}
function closeModal() { $('#globalModal').removeClass('show'); }

function showErrors(errors) {
    $('.form-error').remove();
    if (errors) {
        Object.entries(errors).forEach(([field, msgs]) => {
            const input = $(`[name="${field}"]`);
            input.after(`<p class="form-error">${msgs[0]}</p>`);
        });
    }
}

function renderPagination(meta, callback) {
    if (!meta || meta.last_page <= 1) return '';
    let html = '<div class="pagination">';
    html += `<button class="pagination__btn" onclick="${callback}(1)" ${meta.current_page === 1 ? 'disabled' : ''}><i class="fas fa-angle-double-left"></i></button>`;
    html += `<button class="pagination__btn" onclick="${callback}(${meta.current_page - 1})" ${meta.current_page === 1 ? 'disabled' : ''}><i class="fas fa-angle-left"></i></button>`;

    const start = Math.max(1, meta.current_page - 2);
    const end = Math.min(meta.last_page, meta.current_page + 2);
    for (let i = start; i <= end; i++) {
        html += `<button class="pagination__btn ${i === meta.current_page ? 'active' : ''}" onclick="${callback}(${i})">${i}</button>`;
    }

    html += `<button class="pagination__btn" onclick="${callback}(${meta.current_page + 1})" ${meta.current_page === meta.last_page ? 'disabled' : ''}><i class="fas fa-angle-right"></i></button>`;
    html += `<button class="pagination__btn" onclick="${callback}(${meta.last_page})" ${meta.current_page === meta.last_page ? 'disabled' : ''}><i class="fas fa-angle-double-right"></i></button>`;
    html += '</div>';
    return html;
}

function badgeClass(tipo) {
    if (tipo === 'Personal') return 'card__badge--personal';
    if (tipo === 'Material de Estudio') return 'card__badge--estudio';
    if (tipo === 'Tecnológico') return 'card__badge--tech';
    return '';
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('es-SV', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Lugares de la institución (del proyecto original)
const LUGARES = [
    'Plaza Mayor', 'Plaza Menor', 'Cancha Techada', 'Librería', 'Biblioteca',
    'Edificio Administrativo', 'Cancha Engramada 1', 'Cancha Engramada 2',
    'Cancha de Fesa', 'Templo', 'CFP', 'Aulas de Bachillerato',
    'Aulas de Tercer Ciclo', 'Aulas de Segundo Ciclo', 'Aulas de Primer Ciclo',
    'Capilla', 'Salón Domingo Sabio', 'Multimedia 1', 'Multimedia 2',
    'Salón de Uso Multiples', 'Teatro', 'Canchas de Volleyboy',
    'Cancha Sintetica', 'Edificio de Aulas de Parvularia 1',
    'Edificio de Aulas de Parvularia 2'
];

function lugaresOptions(selected = '') {
    return '<option value="">Seleccione un lugar</option>' +
        LUGARES.map(l => `<option value="${l}" ${l === selected ? 'selected' : ''}>${l}</option>`).join('');
}

// ══════════════════════════════════════
// DOM READY
// ══════════════════════════════════════
$(document).ready(function () {
    // ── Nav dinámico según auth token ──
    if (Auth.isLoggedIn()) {
        $('.nav-auth').show();
        $('.nav-guest').hide();
        if (Auth.isAdmin()) $('.nav-admin').show();
        const user = Auth.getUser();
        if (user) $('#navUserName').text(user.nombre);
    } else {
        $('.nav-auth').hide();
        $('.nav-admin').hide();
        $('.nav-guest').show();
    }

    // Header scroll
    $(window).on('scroll', function () {
        $('.header').toggleClass('scrolled', $(this).scrollTop() > 50);
    });

    // Mobile nav toggle
    $('#navToggle').on('click', function () {
        $('#mainNav').toggleClass('open');
        $(this).find('i').toggleClass('fa-bars fa-times');
    });

    // Modal close
    $('[data-close-modal]').on('click', closeModal);
    $(document).on('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    // Logout
    $('#btnLogout').on('click', async function (e) {
        e.preventDefault();
        try {
            await API.post('/logout');
        } catch {}
        Auth.removeToken();
        window.location.href = '/login';
    });
});
