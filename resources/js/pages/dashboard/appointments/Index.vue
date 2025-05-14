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
        animalTypes: string[] | undefined;
        showAll: boolean | undefined;
    };
    pagination: {
        pageIndex: number;
    };
}

const columns = [
    { accessorKey: 'client.name', header: 'Client' },
    { id: 'animal', header: 'Animal' },
    { id: 'symptoms', header: 'Symptoms' },
    { accessorKey: 'appointment.preferred_date_formatted', header: 'Date' },
    { accessorKey: 'appointment.preferred_time_formatted', header: 'Availability' },
    { id: 'status', header: 'Status' },
    { id: 'actions' },
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
        animalTypes: params?.animalTypes as string[] | undefined,
        showAll: 'showAll' in params || undefined,
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
    const { preferredDate, animalTypes, showAll } = dataManager.value.filters;

    return {
        start: preferredDate?.start?.toString(),
        end: preferredDate?.end?.toString(),
        animalTypes,
        showAll: showAll || undefined,
    };
});

function resetFilterPreferredDates() {
    dataManager.value.filters.preferredDate = {
        start: undefined,
        end: undefined,
    };
}

function updateData() {
    router.get(
        location.pathname,
        {
            page: dataManager.value.pagination.pageIndex,
            ...urlFilters.value,
        },
        {
            only: ['appointments'],
            onStart() {
                loading.value = true;
            },
            onFinish() {
                loading.value = false;
            },
        },
    );
}
</script>

<template>
    <DashboardLayout page-title="Appointments">
        <UCard>
            <div class="space-y-4">
                <!-- Filters -->
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                    <!-- Date filter -->
                    <UPopover>
                        <UButton variant="soft" icon="i-lucide-calendar" size="sm">
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

                    <!-- Animal type list -->
                    <UInputMenu
                        v-model="dataManager.filters.animalTypes"
                        :items="page.props.animalTypes as string[]"
                        variant="soft"
                        size="sm"
                        multiple
                        placeholder="Select animal types"
                    />

                    <!-- Show all appointments -->
                    <USwitch v-if="page.props._can.chooseShowAll" v-model="dataManager.filters.showAll" label="Show all" />
                </div>

                <USeparator />

                <!-- Data -->
                <UTable :columns="columns" :data="data" :loading="loading" class="w-full">
                    <template #animal-cell="{ row }">
                        <div class="font-bold">
                            {{ row.original.animal.name }}
                        </div>
                        <div class="text-xs">
                            {{ row.original.animal.type }}
                        </div>
                        <div class="text-xs">
                            {{ row.original.animal.age_human }}
                        </div>
                    </template>
                    <template #symptoms-cell="{ row }">
                        <div class="max-w-64 min-w-42 text-xs whitespace-normal">
                            {{ row.original.appointment.symptoms }}
                        </div>
                    </template>
                    <template #status-cell="{ row }">
                        <UBadge :color="row.original.medic.name ? 'success' : 'warning'">
                            {{ row.original.medic.name ? 'Assigned' : 'To be assigned' }}
                        </UBadge>
                        <div v-if="row.original.medic.name" class="mt-1">
                            {{ row.original.medic.name }}
                        </div>
                    </template>
                    <template #actions-cell="{ row }">
                        <UButton
                            v-if="row.original._can.update"
                            title="Edit appointment"
                            :href="route('dashboard.appointments.show', { appointment: row.original.appointment.id })"
                            icon="lucide-edit"
                            variant="ghost"
                            size="sm"
                        />
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
