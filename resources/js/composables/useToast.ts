import type { Toast, ToastAction } from '@/components/ui/toast/Toast.vue';
import { ref } from 'vue';

interface ToastOptions {
    title?: string;
    message: string;
    duration?: number;
    persistent?: boolean;
    action?: ToastAction;
    position?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left' | 'top-center' | 'bottom-center';
}

interface ShowToastOptions extends ToastOptions {
    type: 'success' | 'error' | 'warning' | 'info';
}

// Global toast state
const toasts = ref<Toast[]>([]);

// Generate unique ID for toasts
const generateId = () => {
    return `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
};

// Base function to add a toast
const addToast = (options: ShowToastOptions): string => {
    const id = generateId();

    const toast: Toast = {
        id,
        type: options.type,
        title: options.title,
        message: options.message,
        duration: options.duration || (options.type === 'error' ? 7000 : 5000),
        persistent: options.persistent || false,
        action: options.action,
        position: options.position || 'bottom-right',
    };

    toasts.value.push(toast);
    return id;
};

// Remove a specific toast
const removeToast = (id: string) => {
    const index = toasts.value.findIndex((toast) => toast.id === id);
    if (index > -1) {
        toasts.value.splice(index, 1);
    }
};

// Clear all toasts
const clearAllToasts = () => {
    toasts.value = [];
};

// Clear toasts by type
const clearToastsByType = (type: 'success' | 'error' | 'warning' | 'info') => {
    toasts.value = toasts.value.filter((toast) => toast.type !== type);
};

// Main composable function
export const useToast = () => {
    // Success toast
    const success = (options: ToastOptions | string): string => {
        const opts = typeof options === 'string' ? { message: options } : options;

        return addToast({
            ...opts,
            type: 'success',
        });
    };

    // Error toast
    const error = (options: ToastOptions | string): string => {
        const opts = typeof options === 'string' ? { message: options } : options;

        return addToast({
            ...opts,
            type: 'error',
            duration: opts.duration || 7000, // Errors stay longer by default
        });
    };

    // Warning toast
    const warning = (options: ToastOptions | string): string => {
        const opts = typeof options === 'string' ? { message: options } : options;

        return addToast({
            ...opts,
            type: 'warning',
        });
    };

    // Info toast
    const info = (options: ToastOptions | string): string => {
        const opts = typeof options === 'string' ? { message: options } : options;

        return addToast({
            ...opts,
            type: 'info',
        });
    };

    // Promise toast (for async operations)
    const promise = async <T>(
        promise: Promise<T>,
        options: {
            loading: string;
            success: string | ((data: T) => string);
            error: string | ((error: Error) => string);
            position?: ToastOptions['position'];
        },
    ): Promise<T> => {
        const loadingId = info({
            message: options.loading,
            persistent: true,
            position: options.position,
        });

        try {
            const result = await promise;
            removeToast(loadingId);

            const successMessage = typeof options.success === 'function' ? options.success(result) : options.success;

            success({
                message: successMessage,
                position: options.position,
            });

            return result;
        } catch (err) {
            removeToast(loadingId);

            const errorMessage = typeof options.error === 'function' ? options.error(err as Error) : options.error;

            error({
                message: errorMessage,
                position: options.position,
            });

            throw err;
        }
    };

    // Custom toast with full control
    const custom = (options: ShowToastOptions): string => {
        return addToast(options);
    };

    // Dismiss specific toast
    const dismiss = (id: string) => {
        removeToast(id);
    };

    // Utility methods
    const clear = clearAllToasts;
    const clearByType = clearToastsByType;

    return {
        // State (reactive reference, not value)
        toasts,

        // Methods
        success,
        error,
        warning,
        info,
        promise,
        custom,
        dismiss,
        clear,
        clearByType,

        // Internal methods (for components)
        removeToast,
    };
};

// Export types
export type { Toast, ToastAction, ToastOptions };
