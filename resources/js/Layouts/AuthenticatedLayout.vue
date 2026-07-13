<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const flashSuccess = computed(() => page.props.flash.success);
const flashError = computed(() => page.props.flash.error);

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center gap-6">
                        <span class="font-semibold text-gray-900">Payout Tracker</span>
                        <Link
                            :href="route('payouts.index')"
                            class="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Payouts
                        </Link>
                        <Link
                            v-if="user?.is_admin"
                            :href="route('employees.index')"
                            class="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Employees
                        </Link>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">
                            {{ user?.name }}
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs uppercase tracking-wide">
                                {{ user?.is_admin ? 'admin' : 'staff' }}
                            </span>
                        </span>
                        <button
                            @click="logout"
                            class="text-sm text-gray-500 hover:text-gray-900"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
            <div
                v-if="flashSuccess"
                class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800"
            >
                {{ flashSuccess }}
            </div>
            <div
                v-if="flashError"
                class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800"
            >
                {{ flashError }}
            </div>

            <slot />
        </div>
    </div>
</template>
