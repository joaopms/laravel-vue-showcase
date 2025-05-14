<script setup lang="ts">
import DashboardHeader from '@/components/DashboardHeader.vue';
import AppLayout, { AppLayoutProps } from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

defineProps<AppLayoutProps>();

const page = usePage();
const toast = useToast();

// Notifications (toasts)
page.props.flash.success
    ? (() => {
          toast.add({
              title: 'Success!',
              description: page.props.flash.success,
              color: 'success',
              icon: 'lucide-smile',
          });
      })()
    : null;
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
