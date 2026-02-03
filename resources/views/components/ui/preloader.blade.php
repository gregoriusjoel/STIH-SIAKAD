<div id="global-loader" aria-hidden="true"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/70 transition-opacity duration-300" style="display:flex;opacity:1;">
    <x-ui.spinner size="md" class="text-maroon" label="Memuat..." labelClass="text-sm text-maroon" />
</div>

<script>
    (function () {
        const loader = document.getElementById('global-loader');
        if (!loader) return;

        // Start hidden for quick navigations — show only after delay to avoid flashing
        loader.style.display = 'none';
        loader.style.opacity = '0';

        let showTimer = null;

        function doShow() {
            loader.style.display = 'flex';
            void loader.offsetWidth; // force reflow
            loader.style.opacity = '1';
        }

        function doHide() {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 250);
        }

        // Hide once the page fully loaded
        window.addEventListener('load', function () {
            doHide();
        });

        // Show only if the action takes longer than 150ms
        function scheduleShow() {
            if (showTimer) return;
            showTimer = setTimeout(() => {
                doShow();
                showTimer = null;
            }, 150);
        }

        function cancelShow() {
            if (showTimer) {
                clearTimeout(showTimer);
                showTimer = null;
            }
            doHide();
        }

        // Form submissions
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form && form.matches && form.matches('form')) {
                if (form.hasAttribute('data-no-loader')) return;
                scheduleShow();
            }
        }, true);

        // Link clicks (same-origin) — lightweight check
        document.addEventListener('click', function (e) {
            const a = e.target.closest && e.target.closest('a');
            if (!a) return;
            const href = a.getAttribute('href');
            const target = a.getAttribute('target');
            if (!href) return;
            if (href.startsWith('#') || href.startsWith('javascript:') || target === '_blank' || a.hasAttribute('data-no-loader')) return;
            if (href.startsWith('mailto:') || href.startsWith('tel:')) return;
            try {
                const url = new URL(href, window.location.href);
                if (url.origin !== window.location.origin) return;
            } catch (err) {
                // relative link ok
            }
            scheduleShow();
        }, true);

        // If navigation completes / PJAX responses / other events, cancel
        window.addEventListener('pageshow', cancelShow);
        window.addEventListener('popstate', cancelShow);
    })();
</script>