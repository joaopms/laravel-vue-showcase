<script setup lang="ts">
import AppointmentForm from '@/components/AppointmentForm.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import type { InputMenuItem } from '@nuxt/ui';
import { ref } from 'vue';

const page = usePage();
const medics: InputMenuItem = page.props.medics.data.map((medic: { name: string; id: number }) => ({ label: medic.name, value: medic.id }));
const routeParams = { appointment: route().routeParams.appointment };
const data = page.props.appointment.data;

const deleting = ref(false);

function deleteAppointment() {
    deleting.value = true;

    // TODO Use a modal
    if (!confirm('Ready to delete appointment?')) {
        return;
    }

    router.delete(route('dashboard.appointments.destroy', routeParams), {
        onFinish() {
            deleting.value = false;
        },
    });
}
</script>

<template>
    <DashboardLayout page-title="Appointment">
        <UCard>
            <AppointmentForm
                method="put"
                :url="route('dashboard.appointments.update', routeParams)"
                :animal-types="$page.props.animalTypes"
                :times-of-day="$page.props.timesOfDay"
                :initial-data="data"
                client-section-title="Client"
                animal-section-title="Animal"
                appointment-section-title="Appointment"
                extra-section-title="Employee only"
                submit-text="Edit appointment"
            >
                <template #extra="{ form }" v-if="data._can.delete && data._can.assign">
                    <!-- Medic -->
                    <UFormField v-if="data._can.assign" label="Type" name="medic.id" class="w-full sm:w-1/2">
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

                    <UFormField label="Actions" class="w-full sm:w-1/2" v-if="data._can.delete">
                        <UButton variant="outline" color="error" @click.prevent="deleteAppointment" :loading="deleting">Delete appointment</UButton>
                    </UFormField>
                </template>
            </AppointmentForm>
        </UCard>
    </DashboardLayout>
</template>
