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
                :animal-types="$page.props.animalTypes"
                :times-of-day="$page.props.timesOfDay"
                :initial-data="$page.props.appointment.data"
            />
        </UCard>
    </DashboardLayout>
</template>
