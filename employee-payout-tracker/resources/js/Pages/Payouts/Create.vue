<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employees: Array,
});

const form = useForm({
    employee_id: '',
    task: '',
    amount: '',
});

function submit() {
    form.post(route('payouts.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <h1 class="text-lg font-semibold text-gray-900 mb-6">Add Payout</h1>

        <form @submit.prevent="submit" class="bg-white border border-gray-200 rounded-lg p-6 max-w-lg space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                <select
                    v-model="form.employee_id"
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
                >
                    <option value="" disabled>Select an employee</option>
                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                        {{ employee.name }}
                    </option>
                </select>
                <p v-if="form.errors.employee_id" class="mt-1 text-sm text-red-600">{{ form.errors.employee_id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Task</label>
                <input
                    v-model="form.task"
                    type="text"
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
                >
                <p v-if="form.errors.task" class="mt-1 text-sm text-red-600">{{ form.errors.task }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (IQD)</label>
                <input
                    v-model="form.amount"
                    type="number"
                    min="1"
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
                >
                <p v-if="form.errors.amount" class="mt-1 text-sm text-red-600">{{ form.errors.amount }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                >
                    Save Payout
                </button>
                <Link :href="route('payouts.index')" class="text-sm text-gray-500 hover:text-gray-900">Cancel</Link>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
