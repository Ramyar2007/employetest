<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    employees: Object,
});

function destroy(employee) {
    if (confirm(`Delete employee "${employee.name}"? This will also delete their payouts.`)) {
        router.delete(route('employees.destroy', employee.id), { preserveScroll: true });
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-lg font-semibold text-gray-900">Employees</h1>
            <Link
                :href="route('employees.create')"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Add Employee
            </Link>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Payouts</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="employee in employees.data" :key="employee.id">
                        <td class="px-4 py-3 text-gray-900">{{ employee.name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ employee.payouts_count }}</td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <Link :href="route('employees.edit', employee.id)" class="text-gray-600 hover:text-gray-900">Edit</Link>
                            <button @click="destroy(employee)" class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="employees.data.length === 0">
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No employees yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="employees.links.length > 3" class="mt-4 flex flex-wrap gap-1">
            <Link
                v-for="link in employees.links"
                :key="link.label"
                :href="link.url ?? '#'"
                v-html="link.label"
                class="px-3 py-1 rounded-md text-sm border"
                :class="[
                    link.active ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50',
                    !link.url && 'opacity-40 pointer-events-none',
                ]"
            />
        </div>
    </AuthenticatedLayout>
</template>
