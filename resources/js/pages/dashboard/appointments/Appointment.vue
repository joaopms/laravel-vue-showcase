<script setup lang="ts">
import AppointmentForm from '@/components/AppointmentForm.vue';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
</script>

<template>
    <DashboardLayout page-title="Appointment">
        <UCard>
            <AppointmentForm
                method="patch"
                :url="route('dashboard.appointments.edit', { appointment: route().routeParams.appointment })"
                :appointment="$page.props.appointment"
                :animal-types="$page.props.animalTypes"
                :times-of-day="$page.props.timesOfDay"
            />
        </UCard>
    </DashboardLayout>
</template>
