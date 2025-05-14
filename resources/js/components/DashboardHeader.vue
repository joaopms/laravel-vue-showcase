<script setup lang="ts">
import { appName } from '@/lib/consts';
import { type SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import type { NavigationMenuItem } from '@nuxt/ui';
import { computed } from 'vue';

interface DashboardHeaderProps {
    // Reka UI (I think?) merges navigation menus on the same component, but we want independent of each other
    // HACK As a workaround, we're drawing the whole header component twice
    mobile?: boolean;
}

defineProps<DashboardHeaderProps>();
const page = usePage<SharedData>();

const items = <NavigationMenuItem[][]>[
    [
        // {
        //     label: 'Dashboard',
        //     icon: 'i-lucide-house',
        //     href: route('dashboard'),
        // },
        {
            label: 'Appointments',
            icon: 'i-lucide-clipboard-list',
            href: route('dashboard.appointments.index'),
        },
    ],
    [
        {
            label: page.props.auth.user.name,
            icon: 'i-lucide-user',
            disabled: true,
            // href: route('profile.edit'),
        },
        {
            label: 'Logout',
            icon: 'i-lucide-log-out',
            onSelect() {
                router.post(route('logout'));
            },
        },
    ],
];

const currentUrl = computed(() => location.href);
const itemsWithHighlight = items.map(highlightActiveRoute);

// Nuxt UI's `active` is currently broken with Inertia + ziggy, we're highlighting the current item manually
function highlightActiveRoute(items: NavigationMenuItem[]) {
    return items.map((item) => ({ ...item, active: currentUrl.value.startsWith(item.href) }));
}
</script>

<template>
    <header class="items-center gap-2 border-b-1" :class="mobile ? ['flex flex-col px-2 py-2 sm:hidden'] : ['hidden px-4 sm:flex']">
        <ULink raw :href="route('dashboard')" class="text-lg font-bold text-nowrap" :class="mobile ? ['pb-2'] : ['border-r-1 pr-4 pl-2']">
            {{ appName }}
        </ULink>

        <UNavigationMenu
            :items="itemsWithHighlight"
            :orientation="mobile ? 'vertical' : 'horizontal'"
            highlight
            variant="link"
            class="w-full"
            :class="mobile ? ['sm:hidden'] : ['hidden sm:flex']"
            :ui="{ link: 'px-2' }"
        />
    </header>
</template>
