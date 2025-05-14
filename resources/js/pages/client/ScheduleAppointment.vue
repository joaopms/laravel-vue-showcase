<script setup lang="ts">
import AppointmentForm from '@/components/AppointmentForm.vue';
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { ref } from 'vue';

const submitted = ref(false);
</script>

<template>
    <SingleCardLayout
        page-title="Home"
        card-title="Schedule an appointment!"
        card-subtitle="We'll get back to you as soon as possible"
        :card-no-titles="submitted"
    >
        <template #card>
            <div v-if="!submitted">
                <!-- Form -->
                <AppointmentForm
                    method="post"
                    :url="route('public.schedule-appointment')"
                    :animal-types="$page.props.animalTypes"
                    :times-of-day="$page.props.timesOfDay"
                    client-section-title="About you"
                    animal-section-title="About your pet"
                    appointment-section-title="About your visit"
                    submit-text="Schedule appointment"
                    @submit="() => (submitted = true)"
                />
            </div>
            <!-- Success -->
            <div v-else>
                <div class="flex flex-col items-center px-2 text-center sm:px-32">
                    <UIcon name="i-lucide-circle-check-big" class="mt-2 mb-6 size-32 text-green-400" />
                    <h1 class="text-5xl font-black">Success</h1>
                    <p class="mt-3 text-xl">We got your appointment!</p>

                    <p class="mt-12 text-sm">You'll receive an email when your <br />appointment is confirmed.</p>
                    <UButton class="mt-8" variant="soft" size="sm" icon="i-lucide-paw-print" @click="submitted = false">Go back</UButton>
                </div>
            </div>
        </template>

        <template #footer>
            <UButton :href="route('login')" size="sm" variant="ghost">Employee Portal</UButton>
        </template>
    </SingleCardLayout>
</template>
