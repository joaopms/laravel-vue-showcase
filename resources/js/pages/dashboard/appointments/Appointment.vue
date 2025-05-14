<script setup lang="ts">
import AppointmentForm from '@/components/AppointmentForm.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { usePage } from '@inertiajs/vue3';
import type { InputMenuItem } from '@nuxt/ui';

const page = usePage();
const medics: InputMenuItem = page.props.medics.data.map((medic: { name: string; id: number }) => ({ label: medic.name, value: medic.id }));
</script>

<template>
    <DashboardLayout page-title="Appointment">
        <UCard>
            <AppointmentForm
                method="put"
                :url="route('dashboard.appointments.update', { appointment: route().routeParams.appointment })"
                :animal-types="$page.props.animalTypes"
                :times-of-day="$page.props.timesOfDay"
                :initial-data="$page.props.appointment.data"
                client-section-title="Client"
                animal-section-title="Animal"
                appointment-section-title="Appointment"
                extra-section-title="Employee only"
                submit-text="Edit appointment"
            >
                <template #extra="{ form }">
                    <!-- Medic -->
                    <UFormField label="Type" name="medic.id" class="w-full sm:w-1/2">
                        <div class="flex items-center gap-2">
                            <UInputMenu
                                v-model="form.medic.id"
                                :items="medics"
                                value-key="value"
                                data-input-field-name="medic.id"
                                placeholder="Assign to a medic"
                                class="w-full"
                            />
                            <UButton
                                variant="ghost"
                                size="sm"
                                icon="lucide-trash"
                                title="Remove medic"
                                :disabled="!form.medic.id || form.processing"
                                @click.prevent="
                                    () => {
                                        form.medic.id = null;
                                        form.validate();
                                    }
                                "
                            />
                        </div>
                    </UFormField>
                </template>
            </AppointmentForm>
        </UCard>
    </DashboardLayout>
</template>
