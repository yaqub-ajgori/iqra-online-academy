<template>
    <TransitionGroup
        tag="div"
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="transform translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-y-0 opacity-100 sm:translate-x-0"
        leave-to-class="transform translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        class="fixed top-4 right-4 z-50 w-full max-w-sm space-y-4"
    >
        <div
            v-for="notification in notifications"
            :key="notification.id"
            class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <component :is="getIcon(notification.type)" class="h-5 w-5" :class="getIconColor(notification.type)" />
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            {{ notification.title }}
                        </p>
                        <p v-if="notification.message" class="mt-1 text-sm text-gray-600">
                            {{ notification.message }}
                        </p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button
                            @click="removeNotification(notification.id)"
                            class="inline-flex text-gray-400 hover:text-gray-600 focus:text-gray-600 focus:outline-none"
                        >
                            <XIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress bar for auto-dismiss -->
            <div v-if="notification.autoDismiss" class="h-1 bg-gray-200">
                <div
                    class="h-1 transition-all duration-300 ease-linear"
                    :class="getProgressColor(notification.type)"
                    :style="{ width: `${notification.progress}%` }"
                ></div>
            </div>
        </div>
    </TransitionGroup>
</template>

<script setup lang="ts">
import { AlertTriangleIcon, CheckCircleIcon, InfoIcon, XCircleIcon, XIcon } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';

interface Notification {
    id: string;
    type: 'success' | 'error' | 'warning' | 'info';
    title: string;
    message?: string;
    autoDismiss?: boolean;
    duration?: number;
    progress: number;
}

const notifications = ref<Notification[]>([]);
const progressIntervals = ref<Map<string, NodeJS.Timeout>>(new Map());

// Add notification
const addNotification = (notification: Omit<Notification, 'id' | 'progress'>) => {
    const id = Date.now().toString();
    const newNotification: Notification = {
        ...notification,
        id,
        progress: 100,
        autoDismiss: notification.autoDismiss ?? true,
        duration: notification.duration ?? 5000,
    };

    notifications.value.push(newNotification);

    // Auto-dismiss with progress bar
    if (newNotification.autoDismiss) {
        const startTime = Date.now();
        const interval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const progress = Math.max(0, 100 - (elapsed / newNotification.duration!) * 100);

            const index = notifications.value.findIndex((n) => n.id === id);
            if (index !== -1) {
                notifications.value[index].progress = progress;

                if (progress <= 0) {
                    removeNotification(id);
                }
            }
        }, 50);

        progressIntervals.value.set(id, interval);
    }

    return id;
};

// Remove notification
const removeNotification = (id: string) => {
    const index = notifications.value.findIndex((n) => n.id === id);
    if (index !== -1) {
        notifications.value.splice(index, 1);
    }

    // Clear progress interval
    const interval = progressIntervals.value.get(id);
    if (interval) {
        clearInterval(interval);
        progressIntervals.value.delete(id);
    }
};

// Get icon based on type
const getIcon = (type: string) => {
    switch (type) {
        case 'success':
            return CheckCircleIcon;
        case 'error':
            return XCircleIcon;
        case 'warning':
            return AlertTriangleIcon;
        case 'info':
            return InfoIcon;
        default:
            return InfoIcon;
    }
};

// Get icon color based on type
const getIconColor = (type: string) => {
    switch (type) {
        case 'success':
            return 'text-green-500';
        case 'error':
            return 'text-red-500';
        case 'warning':
            return 'text-yellow-500';
        case 'info':
            return 'text-blue-500';
        default:
            return 'text-gray-500';
    }
};

// Get progress bar color based on type
const getProgressColor = (type: string) => {
    switch (type) {
        case 'success':
            return 'bg-green-500';
        case 'error':
            return 'bg-red-500';
        case 'warning':
            return 'bg-yellow-500';
        case 'info':
            return 'bg-blue-500';
        default:
            return 'bg-gray-500';
    }
};

// Convenience methods
const success = (title: string, message?: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'success', title, message, ...options });
};

const error = (title: string, message?: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'error', title, message, ...options });
};

const warning = (title: string, message?: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'warning', title, message, ...options });
};

const info = (title: string, message?: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'info', title, message, ...options });
};

// Cleanup on unmount
onUnmounted(() => {
    progressIntervals.value.forEach((interval) => clearInterval(interval));
    progressIntervals.value.clear();
});

// Expose methods
defineExpose({
    addNotification,
    removeNotification,
    success,
    error,
    warning,
    info,
});
</script>
