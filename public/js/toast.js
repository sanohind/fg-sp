// Toast Notification System
class ToastNotification {
    constructor() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toastContainer')) {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
    }

    show(type, title, message, duration = 3000) {
        // Ensure container exists
        this.init();
        
        const container = document.getElementById('toastContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        
        // Set toast styles based on type
        const styles = this.getToastStyles(type);
        toast.className = styles.className;
        toast.style.cssText = styles.cssText;
        
        // Set toast content
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="${styles.icon} mr-3"></i>
                <div>
                    <h4 class="font-semibold">${title}</h4>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                ${type === 'error' ? '<button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition-colors duration-200"><i class="fas fa-times text-xs"></i></button>' : ''}
            </div>
        `;
        
        // Add toast to container
        container.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);
        
        // Auto remove
        setTimeout(() => {
            this.hide(toast);
        }, duration);
    }

    hide(toast) {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    getToastStyles(type) {
        const baseStyles = {
            className: 'transform translate-x-full opacity-0 transition-all duration-300 shadow-lg rounded-lg px-6 py-3 text-white',
            cssText: 'min-width: 300px; max-width: 400px;'
        };

        switch (type) {
            case 'success':
                return {
                    ...baseStyles,
                    className: baseStyles.className + ' bg-green-500',
                    icon: 'fas fa-check-circle'
                };
            case 'error':
                return {
                    ...baseStyles,
                    className: baseStyles.className + ' bg-red-500',
                    icon: 'fas fa-exclamation-triangle'
                };
            case 'warning':
                return {
                    ...baseStyles,
                    className: baseStyles.className + ' bg-yellow-500',
                    icon: 'fas fa-exclamation-circle'
                };
            case 'info':
                return {
                    ...baseStyles,
                    className: baseStyles.className + ' bg-blue-500',
                    icon: 'fas fa-info-circle'
                };
            default:
                return {
                    ...baseStyles,
                    className: baseStyles.className + ' bg-gray-500',
                    icon: 'fas fa-bell'
                };
        }
    }
}

// Global toast instance - initialize after DOM is ready
let toast;
document.addEventListener('DOMContentLoaded', function() {
    toast = new ToastNotification();
});

// Global functions for backward compatibility
function showSuccessToast(title, message) {
    if (toast) {
        toast.show('success', title, message);
    }
}

function showErrorToast(title, message) {
    if (toast) {
        toast.show('error', title, message, 6000);
    }
}

function showWarningToast(title, message) {
    if (toast) {
        toast.show('warning', title, message);
    }
}

function showInfoToast(title, message) {
    if (toast) {
        toast.show('info', title, message);
    }
}

// Auto-show session messages
document.addEventListener('DOMContentLoaded', function() {
    // Check for session messages (Laravel Blade syntax will be processed server-side)
    if (typeof sessionSuccess !== 'undefined' && sessionSuccess) {
        showSuccessToast('Success!', sessionSuccess);
    }
    
    if (typeof sessionError !== 'undefined' && sessionError) {
        showErrorToast('Error!', sessionError);
    }
    
    if (typeof sessionWarning !== 'undefined' && sessionWarning) {
        showWarningToast('Warning!', sessionWarning);
    }
    
    if (typeof sessionInfo !== 'undefined' && sessionInfo) {
        showInfoToast('Info!', sessionInfo);
    }
});
