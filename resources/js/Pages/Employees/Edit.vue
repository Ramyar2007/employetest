<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employee: Object,
});

const form = useForm({
    name: props.employee.name,
});

function submit() {
    form.put(route('employees.update', props.employee.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <h1 class="text-lg font-semibold text-gray-900 mb-6">Edit Employee</h1>

        <form @submit.prevent="submit" class="bg-white border border-gray-200 rounded-lg p-6 max-w-lg space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    autofocus
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
                >
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                >
                    Update Employee
                </button>
                <Link :href="route('employees.index')" class="text-sm text-gray-500 hover:text-gray-900">Cancel</Link>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
