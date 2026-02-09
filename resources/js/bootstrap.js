import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Axios Global Interceptors for Loader Management
 * 
 * Automatically shows/hides loader for AJAX requests
 * Skip loader for silent requests (add { silent: true } to axios config)
 */

// Request Interceptor - Show loader
window.axios.interceptors.request.use(
    function (config) {
        // Check if request should be silent (no loader)
        const isSilent = config.silent === true;
        const isDownload = config.responseType === 'blob' || config.responseType === 'arraybuffer';
        const isBackgroundRequest = config.background === true;
        
        // Skip loader for silent/download/background requests
        if (isSilent || isDownload || isBackgroundRequest) {
            config._skipLoader = true;
            return config;
        }
        
        // Show loader for blocking requests
        if (window.LoaderManager) {
            const url = config.url || 'unknown';
            window.LoaderManager.show(`axios:${config.method}:${url}`);
            config._loaderShown = true;
        }
        
        return config;
    },
    function (error) {
        // Hide loader on request error
        if (window.LoaderManager && error.config && error.config._loaderShown) {
            window.LoaderManager.hide('axios:request-error');
        }
        return Promise.reject(error);
    }
);

// Response Interceptor - Hide loader
window.axios.interceptors.response.use(
    function (response) {
        // Hide loader on success
        if (window.LoaderManager && response.config && response.config._loaderShown) {
            const url = response.config.url || 'unknown';
            window.LoaderManager.hide(`axios:${response.config.method}:${url}`);
        }
        return response;
    },
    function (error) {
        // Hide loader on error (CRITICAL: prevents stuck loader)
        if (window.LoaderManager && error.config && error.config._loaderShown) {
            const url = error.config?.url || 'unknown';
            window.LoaderManager.hide(`axios:error:${url}`);
        }
        return Promise.reject(error);
    }
);

/**
 * jQuery AJAX Interceptor (if jQuery is loaded)
 * 
 * Provides loader integration for legacy jQuery.ajax() calls
 */
if (window.$ || window.jQuery) {
    $(document).on('ajaxStart', function() {
        // Don't use LoaderManager here as it will conflict with ajaxSend
    });

    $(document).on('ajaxSend', function(event, jqXHR, settings) {
        // Skip if marked as silent
        if (settings.silent === true || settings.background === true) {
            jqXHR._skipLoader = true;
            return;
        }
        
        if (window.LoaderManager && !jqXHR._skipLoader) {
            window.LoaderManager.show(`jquery:${settings.type}:${settings.url}`);
            jqXHR._loaderShown = true;
        }
    });

    $(document).on('ajaxComplete', function(event, jqXHR, settings) {
        if (window.LoaderManager && jqXHR._loaderShown) {
            window.LoaderManager.hide(`jquery:${settings.type}:${settings.url}`);
        }
    });

    $(document).on('ajaxError', function(event, jqXHR, settings, thrownError) {
        if (window.LoaderManager && jqXHR._loaderShown) {
            window.LoaderManager.hide(`jquery:error:${settings.url}`);
        }
    });

    $(document).on('ajaxStop', function() {
        // Fail-safe: ensure loader is hidden when all requests complete
        if (window.LoaderManager && window.LoaderManager.getRequestCount() > 0) {
            console.warn('[Loader] ajaxStop with pending requests, forcing reset');
            window.LoaderManager.forceHide();
        }
    });
}

/**
 * Fetch API Interceptor
 * 
 * Wraps native fetch() to integrate with LoaderManager
 */
if (window.fetch) {
    const originalFetch = window.fetch;
    
    window.fetch = function(...args) {
        const [resource, config] = args;
        
        // Check if silent request
        const isSilent = config?.silent === true;
        const isBackground = config?.background === true;
        
        let loaderShown = false;
        
        if (!isSilent && !isBackground && window.LoaderManager) {
            const url = typeof resource === 'string' ? resource : resource.url;
            window.LoaderManager.show(`fetch:${url}`);
            loaderShown = true;
        }
        
        return originalFetch.apply(this, args)
            .then(response => {
                if (loaderShown && window.LoaderManager) {
                    const url = typeof resource === 'string' ? resource : resource.url;
                    window.LoaderManager.hide(`fetch:${url}`);
                }
                return response;
            })
            .catch(error => {
                if (loaderShown && window.LoaderManager) {
                    const url = typeof resource === 'string' ? resource : resource.url;
                    window.LoaderManager.hide(`fetch:error:${url}`);
                }
                throw error;
            });
    };
}

/**
 * Helper: Download File without triggering loader
 * 
 * Usage:
 *   downloadFile('/admin/import/template/mahasiswa?format=xlsx', 'template_mahasiswa.xlsx');
 */
window.downloadFile = function(url, filename) {
    // Use axios with silent flag
    return axios.get(url, {
        responseType: 'blob',
        silent: true // This prevents loader from showing
    }).then(response => {
        const blob = new Blob([response.data], { 
            type: response.headers['content-type'] || 'application/octet-stream' 
        });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename || 'download';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(link.href);
    }).catch(error => {
        console.error('Download failed:', error);
        alert('Download gagal. Silakan coba lagi.');
    });
};
