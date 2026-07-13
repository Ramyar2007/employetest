<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    payouts: Object,
    filters: Object,
});

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.is_admin);

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? '');

let debounceTimer = null;
watch(search, (value) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => applyFilters({ search: value }), 350);
});

watch(status, (value) => applyFilters({ status: value }));

function applyFilters(overrides) {
    router.get(route('payouts.index'), {
        ...props.filters,
        ...overrides,
    }, { preserveState: true, replace: true });
}

function sortBy(column) {
    const direction = props.filters.sort === column && props.filters.direction === 'asc' ? 'desc' : 'asc';
    applyFilters({ sort: column, direction });
}

function sortIndicator(column) {
    if (props.filters.sort !== column) return '';
    return props.filters.direction === 'asc' ? '▲' : '▼';
}

function changeStatus(payout, event) {
    router.patch(route('payouts.status', payout.id), { status: event.target.value }, { preserveScroll: true });
}

function destroy(payout) {
    if (confirm(`Delete payout "${payout.task}"?`)) {
        router.delete(route('payouts.destroy', payout.id), { preserveScroll: true });
    }
}

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
};

function formatAmount(amount) {
    return new Intl.NumberFormat('en-US').format(amount) + ' IQD';
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-lg font-semibold text-gray-900">Payouts</h1>
            <Link
                :href="route('payouts.create')"
                class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Add Payout
            </Link>
        </div>

        <div class="flex flex-wrap gap-3 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search by task or employee..."
                class="flex-1 min-w-[200px] rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
            >
            <select
                v-model="status"
                class="rounded-md border-gray-300 shadow-sm text-sm focus:border-gray-500 focus:ring-gray-500"
            >
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Employee</th>
                        <th
                            class="px-4 py-3 text-left font-medium text-gray-500 cursor-pointer select-none"
                            @click="sortBy('task')"
                        >
                            Task {{ sortIndicator('task') }}
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium text-gray-500 cursor-pointer select-none"
                            @click="sortBy('amount')"
                        >
                            Amount {{ sortIndicator('amount') }}
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium text-gray-500 cursor-pointer select-none"
                            @click="sortBy('status')"
                        >
                            Status {{ sortIndicator('status') }}
                        </th>
                        <th
                            class="px-4 py-3 text-left font-medium text-gray-500 cursor-pointer select-none"
                            @click="sortBy('created_at')"
                        >
                            Date {{ sortIndicator('created_at') }}
                        </th>
                        <th v-if="isAdmin" class="px-4 py-3 text-right font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="payout in payouts.data" :key="payout.id">
                        <td class="px-4 py-3 text-gray-900">{{ payout.employee.name }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ payout.task }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ formatAmount(payout.amount) }}</td>
                        <td class="px-4 py-3">
                            <select
                                v-if="isAdmin"
                                :value="payout.status"
                                @change="changeStatus(payout, $event)"
                                class="rounded-md border-gray-300 text-xs py-1"
                            >
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                            </select>
                            <span
                                v-else
                                class="inline-flex rounded-full px-2 py-1 text-xs font-medium"
                                :class="statusColors[payout.status]"
                            >
                                {{ payout.status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ new Date(payout.created_at).toLocaleDateString() }}</td>
                        <td v-if="isAdmin" class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <Link :href="route('payouts.edit', payout.id)" class="text-gray-600 hover:text-gray-900">Edit</Link>
                            <button @click="destroy(payout)" class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="payouts.data.length === 0">
                        <td :colspan="isAdmin ? 6 : 5" class="px-4 py-8 text-center text-gray-500">
                            No payouts found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="payouts.links.length > 3" class="mt-4 flex flex-wrap gap-1">
            <Link
                v-for="link in payouts.links"
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
