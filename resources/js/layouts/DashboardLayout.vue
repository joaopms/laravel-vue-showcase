<script setup lang="ts">
import DashboardHeader from '@/components/DashboardHeader.vue';
import AppLayout, { AppLayoutProps } from '@/layouts/AppLayout.vue';

defineProps<AppLayoutProps>();
</script>

<template>
    <AppLayout v-bind="$props">
        <DashboardHeader mobile />
        <DashboardHeader />

        <main class="px-4 py-4 sm:px-6">
            <slot></slot>
        </main>
    </AppLayout>
</template>
