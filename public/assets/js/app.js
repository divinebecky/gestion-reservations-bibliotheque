(function () {
    'use strict';

    const progress = document.getElementById('pageProgress');
    const chartInstances = new Map();

    function startProgress() {
        if (!progress) return;
        progress.classList.add('is-loading');
        progress.querySelector('span').style.width = '35%';
        window.setTimeout(() => {
            if (progress.classList.contains('is-loading')) {
                progress.querySelector('span').style.width = '72%';
            }
        }, 180);
    }

    function finishProgress() {
        if (!progress) return;
        progress.querySelector('span').style.width = '100%';
        window.setTimeout(() => {
            progress.classList.remove('is-loading');
            progress.querySelector('span').style.width = '0';
        }, 260);
    }

    function isAjaxLink(link) {
        if (!link.href || link.target || link.hasAttribute('download')) return false;
        if (link.dataset.noAjax === 'true' || link.closest('form')) return false;

        const url = new URL(link.href, window.location.href);
        if (url.origin !== window.location.origin) return false;

        const route = url.searchParams.get('route') || '';
        return route !== '' && route !== 'logout';
    }

    async function loadPage(url, pushState = true) {
        startProgress();
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                window.location.href = url;
                return;
            }

            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nextContent = doc.querySelector('.content');
            const nextTitle = doc.querySelector('.topbar h1');
            const nextSubtitle = doc.querySelector('.topbar p');
            const nextDocumentTitle = doc.querySelector('title');

            if (!nextContent) {
                window.location.href = url;
                return;
            }

            document.querySelector('.content').innerHTML = nextContent.innerHTML;
            if (nextTitle) document.querySelector('.topbar h1').textContent = nextTitle.textContent;
            if (nextSubtitle) document.querySelector('.topbar p').textContent = nextSubtitle.textContent;
            if (nextDocumentTitle) document.title = nextDocumentTitle.textContent;

            if (pushState) {
                window.history.pushState({ ajax: true }, '', url);
            }

            updateActiveMenu(url);
            document.querySelector('.sidebar')?.classList.remove('show');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            initPage(document);
        } catch (error) {
            window.location.href = url;
        } finally {
            finishProgress();
        }
    }

    function updateActiveMenu(url) {
        const targetRoute = new URL(url, window.location.href).searchParams.get('route') || 'dashboard';
        document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
            const linkRoute = new URL(link.href, window.location.href).searchParams.get('route') || 'dashboard';
            const isActive = targetRoute === linkRoute || targetRoute.startsWith(linkRoute + '/');
            link.classList.toggle('active', isActive);
        });
    }

    function bindAjaxNavigation(scope) {
        scope.querySelectorAll('a').forEach((link) => {
            if (link.dataset.ajaxBound === 'true') return;
            link.dataset.ajaxBound = 'true';
            link.addEventListener('click', (event) => {
                if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                if (!isAjaxLink(link)) return;
                event.preventDefault();
                loadPage(link.href);
            });
        });
    }

    function bindAjaxForms(scope) {
        scope.querySelectorAll('form[method="get"]').forEach((form) => {
            if (form.dataset.ajaxFormBound === 'true') return;
            form.dataset.ajaxFormBound = 'true';
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const params = new URLSearchParams(new FormData(form));
                const url = new URL(form.getAttribute('action') || window.location.pathname, window.location.href);
                url.search = params.toString();
                loadPage(url.toString());
            });
        });
    }

    function bindSidebar() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (!sidebarToggle || sidebarToggle.dataset.bound === 'true') return;
        sidebarToggle.dataset.bound = 'true';
        sidebarToggle.addEventListener('click', () => {
            document.querySelector('.sidebar')?.classList.toggle('show');
        });
    }

    function bindValidation(scope) {
        scope.querySelectorAll('.needs-validation').forEach((form) => {
            if (form.dataset.validationBound === 'true') return;
            form.dataset.validationBound = 'true';
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    }

    function bindSearch(scope) {
        scope.querySelectorAll('.live-search').forEach((input) => {
            if (input.dataset.searchBound === 'true') return;
            input.dataset.searchBound = 'true';
            input.addEventListener('input', () => {
                const table = input.closest('.toolbar')?.nextElementSibling?.querySelector('table');
                if (!table) return;
                const term = input.value.toLowerCase();
                table.querySelectorAll('tbody tr').forEach((row) => {
                    row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
                });
            });
        });
    }

    function bindSorting(scope) {
        scope.querySelectorAll('.sortable-table th').forEach((header, index) => {
            if (header.dataset.sortBound === 'true') return;
            header.dataset.sortBound = 'true';
            header.addEventListener('click', () => {
                const table = header.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const direction = header.dataset.direction === 'asc' ? 'desc' : 'asc';
                header.dataset.direction = direction;
                rows.sort((a, b) => {
                    const av = a.children[index]?.innerText.trim() || '';
                    const bv = b.children[index]?.innerText.trim() || '';
                    return direction === 'asc' ? av.localeCompare(bv, 'fr', { numeric: true }) : bv.localeCompare(av, 'fr', { numeric: true });
                });
                rows.forEach((row) => tbody.appendChild(row));
            });
        });
    }

    function bindPhotoPreview(scope) {
        const photoInput = scope.querySelector('#photo');
        if (!photoInput || photoInput.dataset.photoBound === 'true') return;
        photoInput.dataset.photoBound = 'true';
        photoInput.addEventListener('change', () => {
            const file = photoInput.files?.[0];
            const preview = document.getElementById('photoPreview');
            const fallback = document.getElementById('photoFallback');
            if (!file || !preview) return;
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
            if (fallback) fallback.hidden = true;
        });
    }

    function renderCharts(scope) {
        chartInstances.forEach((chart) => chart.destroy());
        chartInstances.clear();

        const monthly = scope.querySelector('#monthlyChart');
        if (monthly && window.Chart) {
            chartInstances.set('monthlyChart', new Chart(monthly, {
                type: 'bar',
                data: {
                    labels: JSON.parse(monthly.dataset.labels || '[]'),
                    datasets: [{ label: 'Reservations', data: JSON.parse(monthly.dataset.values || '[]'), backgroundColor: '#315c9f' }]
                },
                options: { responsive: true, plugins: { legend: { display: false } } }
            }));
        }

        const status = scope.querySelector('#statusChart');
        if (status && window.Chart) {
            chartInstances.set('statusChart', new Chart(status, {
                type: 'doughnut',
                data: {
                    labels: JSON.parse(status.dataset.labels || '[]'),
                    datasets: [{ data: JSON.parse(status.dataset.values || '[]'), backgroundColor: ['#0f9f8f', '#f59e0b', '#ef4444', '#64748b'] }]
                },
                options: { responsive: true }
            }));
        }
    }

    function initPage(scope) {
        bindAjaxNavigation(scope);
        bindAjaxForms(scope);
        bindValidation(scope);
        bindSearch(scope);
        bindSorting(scope);
        bindPhotoPreview(scope);
        renderCharts(scope);
    }

    bindSidebar();
    initPage(document);

    window.addEventListener('popstate', () => {
        loadPage(window.location.href, false);
    });
})();
