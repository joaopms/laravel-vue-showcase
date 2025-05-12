<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { formatDate, isSameDate } from '@/lib/dates';
import { router, usePage } from '@inertiajs/vue3';
import { CalendarDate, parseDate } from '@internationalized/date';
import type { Ref } from 'vue';
import { computed, ref, watch } from 'vue';

interface AppointmentsDataManager {
    filters: {
        preferredDate: {
            start: CalendarDate | undefined;
            end: CalendarDate | undefined;
        };
    };
    pagination: {
        pageIndex: number;
    };
}

const columns = [
    { accessorKey: 'client.name', header: 'Client' },
    { accessorKey: 'animal.name', header: 'Pet Name' },
    { accessorKey: 'animal.type', header: 'Pet Type' },
    { accessorKey: 'preferred_date', header: 'Date' },
    { accessorKey: 'preferred_time', header: 'Availability' },
    { id: 'status', header: 'Status' },
];

const loading = ref(false);

const page = usePage();
const params = route().queryParams;

const _appointments = computed(() => page.props.appointments);
const meta = computed(() => _appointments.value.meta);
const data = computed(() => _appointments.value.data);

const dataManager = ref<AppointmentsDataManager>({
    filters: {
        preferredDate: {
            start: params.start ? parseDate(params.start as string) : undefined,
            end: params.end ? parseDate(params.end as string) : undefined,
        },
    },
    pagination: {
        pageIndex: parseInt(meta.value.current_page),
    },
}) as Ref<AppointmentsDataManager>;

const datesFilter = computed(() => dataManager.value.filters.preferredDate);
watch(
    dataManager,
    () => {
        updateData();
    },
    { deep: true },
);

const urlFilters = computed(() => {
    const preferredDate = dataManager.value.filters.preferredDate;

    return {
        start: preferredDate?.start?.toString(),
        end: preferredDate?.end?.toString(),
    };
});

function resetFilterPreferredDates() {
    dataManager.value.filters.preferredDate = {
        start: undefined,
        end: undefined,
    };
}

function updateData() {
    router.reload({
        data: {
            page: dataManager.value.pagination.pageIndex,
            ...urlFilters.value,
        },
        only: ['appointments'],
        onStart() {
            loading.value = true;
        },
        onFinish() {
            loading.value = false;
        },
    });
}
</script>

<template>
    <DashboardLayout page-title="Appointments">
        <UCard>
            <div class="space-y-4">
                <!-- Filters -->
                <div class="flex items-center gap-2">
                    <!-- Date filter -->
                    <UPopover>
                        <UButton variant="subtle" icon="i-lucide-calendar" size="sm">
                            <template v-if="datesFilter.start">
                                {{ formatDate(datesFilter.start) }}

                                <template v-if="datesFilter.end && !isSameDate(datesFilter)">
                                    -
                                    {{ formatDate(datesFilter.end) }}
                                </template>
                            </template>
                            <template v-else>Pick a date</template>
                        </UButton>

                        <template #content>
                            <div class="flex flex-col items-center gap-2 p-2">
                                <UCalendar range v-model="dataManager.filters.preferredDate" />
                                <USeparator />
                                <UButton size="sm" variant="soft" @click="resetFilterPreferredDates()">Reset</UButton>
                            </div>
                        </template>
                    </UPopover>
                </div>

                <USeparator />

                <!-- Data -->
                <UTable :columns="columns" :data="data" :loading="loading" class="w-full">
                    <template #status-cell="{ row }">
                        <UBadge :color="row.original.medic ? 'success' : 'warning'">
                            {{ row.original.medic ? 'Assigned to ' + row.original.medic.name : 'To assign' }}
                        </UBadge>
                    </template>
                </UTable>

                <!-- Pagination -->
                <UPagination
                    v-model:page="dataManager.pagination.pageIndex"
                    :items-per-page="meta.per_page"
                    :total="meta.total"
                    class="flex justify-center"
                />
            </div>
        </UCard>
    </DashboardLayout>
</template>
