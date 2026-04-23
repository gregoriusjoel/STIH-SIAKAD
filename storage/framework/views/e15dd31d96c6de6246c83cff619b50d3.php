<div id="global-loader" aria-hidden="true"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/70 backdrop-blur-sm transition-opacity duration-300"
    style="display:none;opacity:0;">
    <div class="text-center">
        <?php if (isset($component)) { $__componentOriginal7ee43febc033d8a87ae157694e6933ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ee43febc033d8a87ae157694e6933ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.spinner','data' => ['size' => 'md','class' => 'text-maroon','label' => 'Memuat...','labelClass' => 'text-sm text-maroon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.spinner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md','class' => 'text-maroon','label' => 'Memuat...','labelClass' => 'text-sm text-maroon']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ee43febc033d8a87ae157694e6933ee)): ?>
<?php $attributes = $__attributesOriginal7ee43febc033d8a87ae157694e6933ee; ?>
<?php unset($__attributesOriginal7ee43febc033d8a87ae157694e6933ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ee43febc033d8a87ae157694e6933ee)): ?>
<?php $component = $__componentOriginal7ee43febc033d8a87ae157694e6933ee; ?>
<?php unset($__componentOriginal7ee43febc033d8a87ae157694e6933ee); ?>
<?php endif; ?>
        <div id="loader-debug" class="mt-2 text-xs text-gray-500" style="display:none;"></div>
    </div>
</div>

<script>
    /**
     * Global Loader Manager
     * Robust loading overlay system with request tracking, fail-safe timeout, and AJAX lifecycle control
     * 
     * Features:
     * - Request counter for multiple parallel requests
     * - Fail-safe auto-hide after 5s
     * - Download-safe (won't trigger on file downloads)
     * - AJAX/Axios interceptor ready
     * - SweetAlert2 compatible
     * - Dark mode support
     */
    (function () {
        'use strict';

        // ========== Configuration ==========
        const CONFIG = {
            showDelay: 300,          // Delay before showing loader (ms)
            failSafeTimeout: 2000,   // Auto-hide after 2s
            fadeOutDuration: 250,    // Fade out animation duration (ms)
            debugMode: false         // Set true to enable debug logging
        };

        // ========== State Management ==========
        const state = {
            requestCounter: 0,       // Track multiple parallel requests
            showTimer: null,         // Delayed show timer
            failSafeTimer: null,     // Fail-safe timeout timer
            isVisible: false,        // Current visibility state
            lastAction: ''           // For debugging
        };

        // ========== DOM Elements ==========
        const loader = document.getElementById('global-loader');
        const debugEl = document.getElementById('loader-debug');
        
        if (!loader) {
            console.warn('Global loader element not found');
            return;
        }

        // ========== Core Loader Functions ==========
        
        function debugLog(message) {
            if (CONFIG.debugMode) {
                console.log(`[Loader] ${message}`);
                if (debugEl) {
                    debugEl.textContent = `Requests: ${state.requestCounter} | ${message}`;
                    debugEl.style.display = 'block';
                }
            }
            state.lastAction = message;
        }

        function doShow() {
            if (state.isVisible) return;
            
            loader.style.display = 'flex';
            void loader.offsetWidth; // Force reflow
            loader.style.opacity = '1';
            loader.setAttribute('aria-hidden', 'false');
            state.isVisible = true;
            
            debugLog('Loader shown');
            startFailSafe();
        }

        function doHide() {
            if (!state.isVisible && !loader.style.opacity) return;
            
            loader.style.opacity = '0';
            loader.setAttribute('aria-hidden', 'true');
            
            setTimeout(() => {
                if (state.requestCounter === 0) {
                    loader.style.display = 'none';
                    state.isVisible = false;
                }
            }, CONFIG.fadeOutDuration);
            
            debugLog('Loader hidden');
            clearFailSafe();
        }

        // ========== Request Counter Management ==========
        
        function incrementRequests(source = 'unknown') {
            state.requestCounter++;
            debugLog(`Request started from ${source} (count: ${state.requestCounter})`);
            
            if (state.requestCounter === 1) {
                scheduleShow();
            }
        }

        function decrementRequests(source = 'unknown') {
            state.requestCounter = Math.max(0, state.requestCounter - 1);
            debugLog(`Request ended from ${source} (count: ${state.requestCounter})`);
            
            if (state.requestCounter === 0) {
                cancelShow();
            }
        }

        function resetRequests(reason = 'manual reset') {
            const prevCount = state.requestCounter;
            state.requestCounter = 0;
            cancelShow();
            debugLog(`All requests reset: ${prevCount} → 0 (${reason})`);
        }

        // ========== Show/Hide Scheduling ==========
        
        function scheduleShow() {
            if (state.showTimer) return;
            
            state.showTimer = setTimeout(() => {
                if (state.requestCounter > 0) {
                    doShow();
                }
                state.showTimer = null;
            }, CONFIG.showDelay);
            
            debugLog('Show scheduled');
        }

        function cancelShow() {
            if (state.showTimer) {
                clearTimeout(state.showTimer);
                state.showTimer = null;
                debugLog('Show cancelled');
            }
            doHide();
        }

        // ========== Fail-Safe Protection ==========
        
        function startFailSafe() {
            clearFailSafe();
            
            state.failSafeTimer = setTimeout(() => {
                console.warn('[Loader] Fail-safe triggered: forcing hide after 5s');
                resetRequests('fail-safe timeout');
            }, CONFIG.failSafeTimeout);
        }

        function clearFailSafe() {
            if (state.failSafeTimer) {
                clearTimeout(state.failSafeTimer);
                state.failSafeTimer = null;
            }
        }

        // ========== Global API ==========
        
        window.LoaderManager = {
            show: function(source = 'manual') {
                incrementRequests(source);
            },
            hide: function(source = 'manual') {
                decrementRequests(source);
            },
            forceHide: function() {
                resetRequests('force hide');
            },
            isVisible: function() {
                return state.isVisible;
            },
            getRequestCount: function() {
                return state.requestCounter;
            },
            enableDebug: function() {
                CONFIG.debugMode = true;
                if (debugEl) debugEl.style.display = 'block';
            },
            disableDebug: function() {
                CONFIG.debugMode = false;
                if (debugEl) debugEl.style.display = 'none';
            }
        };

        // ========== Page Load Handling ==========
        
        window.addEventListener('load', function () {
            resetRequests('page loaded');
        });

        // ========== Form Submission Handling ==========
        
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || !form.matches || !form.matches('form')) return;

            // Skip forms that open a new tab/window (e.g. print/download)
            const target = (form.getAttribute('target') || '').toLowerCase();
            if (target === '_blank') {
                debugLog('Form submit ignored (target _blank)');
                return;
            }
            
            // Skip if form has data-no-loader attribute
            if (form.hasAttribute('data-no-loader')) {
                debugLog('Form submit ignored (data-no-loader)');
                return;
            }
            
            // Skip if SweetAlert is open
            if (document.querySelector('.swal2-container')) {
                debugLog('Form submit ignored (SweetAlert open)');
                return;
            }
            
            // Skip DELETE forms (usually handled by SweetAlert)
            try {
                if (form.querySelector('input[name="_method"][value="DELETE"]')) {
                    debugLog('Form submit ignored (DELETE method)');
                    return;
                }
            } catch (err) {
                // Ignore
            }
            
            incrementRequests('form-submit');
        }, true);

        // ========== Link Click Handling ==========
        
        document.addEventListener('click', function (e) {
            const a = e.target.closest && e.target.closest('a');
            if (!a) return;
            
            const href = a.getAttribute('href');
            const target = a.getAttribute('target');
            
            if (!href) return;
            
            // Skip downloads, external links, anchors, etc.
            if (a.hasAttribute('data-no-loader')) {
                debugLog('Link click ignored (data-no-loader)');
                return;
            }
            
            if (a.hasAttribute('download')) {
                debugLog('Link click ignored (download attribute)');
                return;
            }
            
            if (href.startsWith('#') || href.startsWith('javascript:')) {
                return;
            }
            
            if (target === '_blank') {
                return;
            }
            
            if (href.startsWith('mailto:') || href.startsWith('tel:')) {
                return;
            }
            
            // Check if it's a download link (template, export, etc.)
            if (href.includes('/download') || href.includes('/export') || href.includes('/template')) {
                debugLog('Link click ignored (download URL pattern)');
                return;
            }
            
            // Check file extensions
            const fileExtensions = /\.(pdf|xlsx?|csv|docx?|zip|rar|jpg|png|gif)$/i;
            if (fileExtensions.test(href)) {
                debugLog('Link click ignored (file extension)');
                return;
            }
            
            // Check if same origin
            try {
                const url = new URL(href, window.location.href);
                if (url.origin !== window.location.origin) {
                    return;
                }
            } catch (err) {
                // Relative link is ok
            }
            
            incrementRequests('link-click');
        }, true);

        // ========== Navigation Events ==========
        
        window.addEventListener('pageshow', function() {
            resetRequests('pageshow');
        });
        
        window.addEventListener('popstate', function() {
            resetRequests('popstate');
        });

        // ========== SweetAlert Detection ==========
        
        if (window.MutationObserver) {
            const observer = new MutationObserver(function (mutations) {
                for (const m of mutations) {
                    for (const node of m.addedNodes) {
                        try {
                            if (node && node.classList && node.classList.contains('swal2-container')) {
                                resetRequests('SweetAlert opened');
                                return;
                            }
                        } catch (err) {
                            // Ignore
                        }
                    }
                }
            });
            observer.observe(document.documentElement || document.body, { 
                childList: true, 
                subtree: true 
            });
        }

        // ========== Visibility Change (tab switch) ==========
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && state.isVisible) {
                debugLog('Tab hidden, checking requests');
                // Don't force hide, but check if stuck
                if (state.requestCounter > 5) {
                    console.warn('[Loader] Too many pending requests, resetting');
                    resetRequests('tab hidden with many requests');
                }
            }
        });

        // ========== Emergency Reset (Escape key) ==========
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && state.isVisible && e.shiftKey && e.ctrlKey) {
                console.warn('[Loader] Emergency reset via Shift+Ctrl+Escape');
                resetRequests('emergency escape');
            }
        });

        debugLog('Loader Manager initialized');
    })();
</script><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/components/ui/preloader.blade.php ENDPATH**/ ?>