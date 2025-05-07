<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();

const animalTypes = ref<string[]>(page.props.animalTypes);
const state = ref({
    client: {
        name: '',
        email: '',
    },
    animal: {
        name: '',
        type: '',
        ageMonths: null,
    },
    appointment: {
        preferredDate: '',
        preferredTime: [],
        symptoms: '',
    },
});

function onCreateAnimalType(item: string) {
    console.log('New animal type: ' + item);
    animalTypes.value.push(item);
    state.value.animal.type = item;
}
</script>

<template>
    <Head title="New Appointment" />

    <UApp>
        <UContainer class="flex h-full flex-col items-center justify-center p-4 sm:p-6">
            <!-- Header -->
            <UIcon name="i-lucide-hospital" class="mt-8 size-32" />
            <h1 class="mt-6 text-5xl">{{ $page.props.name }}</h1>

            <!-- Appointment card -->
            <UCard class="mt-16 sm:p-4">
                <div class="mt-6 mb-12 px-4 text-center sm:mt-0">
                    <h2 class="text-3xl">Schedule an appointment with us!</h2>
                    <p class="mt-2">We'll get back to you as soon as possible.</p>
                </div>

                <UForm :state="state" class="space-y-4">
                    <p class="mb-2 text-2xl/12">About you</p>
                    <UFormField label="Name" name="name" required>
                        <UInput v-model="state.client.name" placeholder="Your name" class="w-full" />
                    </UFormField>

                    <UFormField label="Email" name="email" description="Used to contact you about your appointment" required>
                        <UInput v-model="state.client.email" type="email" placeholder="Your email" class="w-full" />
                    </UFormField>

                    <USeparator class="mb-0 py-6" />

                    <p class="mb-2 pt-0 text-2xl/12">About your pet</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <UFormField label="Name" name="name" required>
                            <UInput v-model="state.animal.name" class="w-full" placeholder="Your pet's name" />
                        </UFormField>
                        <UFormField label="Type" name="type" required>
                            <UInputMenu
                                v-model="state.animal.type"
                                placeholder="Your pet type"
                                create-item="always"
                                :items="animalTypes"
                                @create="onCreateAnimalType"
                                class="w-full"
                            />
                        </UFormField>
                        <UFormField label="Age" name="age" hint="in months" required>
                            <UInput v-model="state.animal.ageMonths" class="w-full" placeholder="Your pet's age (in months)" />
                        </UFormField>
                    </div>

                    <USeparator class="mb-0 py-6" />

                    <p class="mb-2 pt-0 text-2xl/12">About your visit</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="space-y-2 md:w-1/3">
                            <UFormField label="Preferred Time" name="preferred_date" required>
                                <UInput v-model="state.appointment.preferredDate" type="date" class="w-full" />
                            </UFormField>
                            <UCheckboxGroup
                                v-model="state.appointment.preferredTime"
                                orientation="horizontal"
                                name="preferred_time"
                                :items="['Morning', 'Afternoon']"
                                required
                            />
                        </div>
                        <UFormField label="Symptoms" name="date" class="w-full md:w-2/3" required>
                            <UTextarea v-model="state.appointment.symptoms" placeholder="Why are you visiting us?" rows="4" class="w-full" />
                        </UFormField>
                    </div>

                    <div class="mt-12 text-right">
                        <UButton type="submit" label="Schedule appointment" color="primary" size="lg" trailing-icon="i-lucide-paw-print" />
                    </div>
                </UForm>
            </UCard>
        </UContainer>
    </UApp>
</template>
