<script setup lang="ts">
import DashboardHeader from '@/components/DashboardHeader.vue';
import AppLayout, { AppLayoutProps } from '@/layouts/AppLayout.vue';

defineProps<AppLayoutProps>();
</script>

<template>
    <AppLayout v-bind="$props">
        <DashboardHeader mobile />
        <DashboardHeader />

        <UContainer as="main" class="py-4 lg:px-6">
            <slot></slot>
        </UContainer>
    </AppLayout>
</template>
