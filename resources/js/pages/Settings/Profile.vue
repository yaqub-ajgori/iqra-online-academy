<template>
    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-3xl px-4 py-12">
            <div class="rounded-lg bg-white p-6 shadow">
                <h1 class="mb-6 text-2xl font-bold text-gray-900">Profile Settings</h1>

                <form @submit.prevent="updateProfile" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                            :disabled="form.processing"
                        >
                            Update Profile
                        </button>

                        <button
                            type="button"
                            @click="deleteAccount"
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                        >
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    user: Object,
});

const form = reactive({
    name: props.user.name,
    email: props.user.email,
    processing: false,
    errors: {},
});

const updateProfile = () => {
    form.processing = true;
    form.errors = {};

    router.patch(
        route('settings.profile.update'),
        {
            name: form.name,
            email: form.email,
        },
        {
            onSuccess: () => {
                form.processing = false;
            },
            onError: (errors) => {
                form.errors = errors;
                form.processing = false;
            },
        },
    );
};

const deleteAccount = () => {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        const password = prompt('Please enter your password to confirm:');
        if (password) {
            router.delete(route('settings.profile.destroy'), {
                data: { password },
            });
        }
    }
};
</script>
