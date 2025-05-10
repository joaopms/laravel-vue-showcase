<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

export interface AppLayoutProps {
    pageTitle: string;
}

defineProps<AppLayoutProps>();
</script>

<template>
    <Head :title="pageTitle" />

    <UApp>
        <slot />
    </UApp>
</template>
