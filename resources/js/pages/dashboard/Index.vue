<script setup lang="ts">
import PlaceholderPattern from '@/components_old/PlaceholderPattern.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
</script>

<template>
    <DashboardLayout page-title="Dashboard">
        hi!
        <main class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern />
                    </div>
                    <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern />
                    </div>
                    <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern />
                    </div>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                    <PlaceholderPattern />
                </div>
            </div>
        </main>
    </DashboardLayout>
</template>
