<div id="global-loader"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-500">
    <x-ui.spinner size="lg" />
</div>

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    });
</script>