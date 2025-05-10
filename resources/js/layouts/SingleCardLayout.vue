<script setup lang="ts">
import AppLayout, { AppLayoutProps } from '@/layouts/AppLayout.vue';
import { appName } from '@/lib/consts';

interface SingleCardLayoutProps extends AppLayoutProps {
    cardTitle?: string;
    cardSubtitle?: string;
    cardNoTitles?: boolean;
}

interface SingleCardLayoutSlots {
    card(): any;

    footer(): any;
}

defineProps<SingleCardLayoutProps>();
defineSlots<SingleCardLayoutSlots>();
</script>

<template>
    <AppLayout v-bind="{ pageTitle }">
        <UContainer class="flex h-full flex-col items-center justify-center p-4 sm:p-6">
            <!-- Page header -->
            <UIcon name="i-lucide-hospital" class="mt-12 size-32" />
            <h1 class="mt-6 text-center text-5xl font-black">{{ appName }}</h1>

            <!-- Card -->
            <UCard class="mt-16 sm:p-4">
                <!-- Card header -->
                <div v-if="!cardNoTitles && (cardTitle || cardSubtitle)" class="mt-6 mb-12 px-4 text-center sm:mt-0">
                    <h2 v-if="cardTitle" class="text-4xl font-bold">{{ cardTitle }}</h2>
                    <p v-if="cardSubtitle" class="mt-4">{{ cardSubtitle }}</p>
                </div>
                <slot name="card" />
            </UCard>

            <div v-if="$slots.footer" class="mt-4">
                <slot name="footer" />
            </div>
        </UContainer>
    </AppLayout>
</template>
