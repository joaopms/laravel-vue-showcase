<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import type { Form } from '@nuxt/ui/runtime/types/form.d.ts';
import { useForm } from 'laravel-precognition-vue-inertia';
import { capitalize, computed, ref, useTemplateRef } from 'vue';

const page = usePage();

const animalTypes = ref(page.props.animalTypes as string[]);
const timeOfDay = (page.props.timeOfDay as string[]).map((time) => ({
    value: time,
    label: capitalize(time),
}));

const form = useForm(
    'post',
    route('public.schedule-appointment'),
    {
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
    },
    {
        onFinish() {
            // Show errors after validating (using precognition) on the server
            showFormErrors();
        },
    },
);
const formRef = useTemplateRef<Form<any>>('formRef');
const formErrors = computed(() => Object.entries(form.errors).map(([name, message]) => ({ name, message })));

function showFormErrors() {
    formRef.value?.setErrors(formErrors.value);
}

function validateForm() {
    // Get touched fields from the Nuxt UI form
    const fields = Array.from(formRef.value?.touchedFields as Set<string>);
    // Add the touched fields to the Laravel Precognition form
    form.touch(fields);
    // Validate on the server, using precognition
    form.validate();

    return formRef.value?.getErrors() ?? []; // Don't overwrite the errors
}

async function submitForm() {
    form.submit({
        preserveScroll: true,
        onError: () => {
            // Show errors after failed submission
            showFormErrors();
        },
    });
}

function createAnimalType(item: string) {
    animalTypes.value.push(item);
    form.animal.type = item;
}
</script>

<template>
    <Head title="New Appointment" />

    <UApp>
        <UContainer class="flex h-full flex-col items-center justify-center p-4 sm:p-6">
            <!-- Page header -->
            <UIcon name="i-lucide-hospital" class="mt-8 size-32" />
            <h1 class="mt-6 text-5xl">{{ page.props.name }}</h1>

            <!-- Appointment card -->
            <UCard class="mt-16 sm:p-4">
                <!-- Appointment card: header-->
                <div class="mt-6 mb-12 px-4 text-center sm:mt-0">
                    <h2 class="text-3xl">Schedule an appointment with us!</h2>
                    <p class="mt-2">We'll get back to you as soon as possible.</p>
                </div>

                <!-- Appointment card: form-->
                <UForm
                    ref="formRef"
                    :state="form"
                    :validate="validateForm"
                    :validate-on="['change']"
                    :validate-on-input-delay="0"
                    :disabled="form.processing"
                    @submit="submitForm"
                    class="space-y-4"
                >
                    <!-- Client -->
                    <p class="mb-2 text-2xl/12">About you</p>

                    <!--Client: name -->
                    <UFormField label="Name" name="client.name" required>
                        <UInput v-model="form.client.name" placeholder="Your name" class="w-full" />
                    </UFormField>
                    <!--Client: email-->
                    <UFormField label="Email" name="client.email" description="Used to contact you about your appointment" required>
                        <UInput v-model="form.client.email" type="email" placeholder="Your email" class="w-full" />
                    </UFormField>

                    <USeparator class="mb-0 py-6" />

                    <!-- Pet -->
                    <p class="mb-2 pt-0 text-2xl/12">About your pet</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <!-- Pet: name-->
                        <UFormField label="Name" name="animal.name" required>
                            <UInput v-model="form.animal.name" class="w-full" placeholder="Your pet's name" />
                        </UFormField>
                        <!-- Pet: type -->
                        <UFormField label="Type" name="animal.type" required>
                            <UInputMenu
                                v-model="form.animal.type"
                                placeholder="Your pet type"
                                create-item="always"
                                :items="animalTypes"
                                @create="createAnimalType"
                                class="w-full"
                            />
                        </UFormField>
                        <!-- Pet: age -->
                        <UFormField label="Age" name="animal.ageMonths" hint="in months" required>
                            <UInput v-model="form.animal.ageMonths" class="w-full" placeholder="Your pet's age (in months)" />
                        </UFormField>
                    </div>

                    <USeparator class="mb-0 py-6" />

                    <!-- Appointment -->
                    <p class="mb-2 pt-0 text-2xl/12">About your visit</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="space-y-2 md:w-1/3">
                            <!-- Appointment: date + time -->
                            <UFormField label="Preferred Time" name="appointment.preferredDate" required>
                                <UInput v-model="form.appointment.preferredDate" type="date" class="w-full" />
                            </UFormField>
                            <UFormField name="appointment.preferredTime">
                                <UCheckboxGroup
                                    v-model="form.appointment.preferredTime"
                                    orientation="horizontal"
                                    name="appointment.preferredTime"
                                    :items="timeOfDay"
                                />
                            </UFormField>
                        </div>
                        <!-- Appointment: symptoms-->
                        <UFormField label="Symptoms" name="appointment.symptoms" class="w-full md:w-2/3" required>
                            <UTextarea v-model="form.appointment.symptoms" placeholder="Why are you visiting us?" :rows="4" class="w-full" />
                        </UFormField>
                    </div>

                    <!-- Form: controls -->
                    <div class="mt-12 flex flex-col-reverse items-center justify-end gap-3 sm:flex-row">
                        <div v-if="form.hasErrors" class="text-sm text-red-400">Please review and fix issues</div>
                        <UButton
                            type="submit"
                            :disabled="form.hasErrors"
                            :loading="form.processing"
                            color="primary"
                            size="lg"
                            icon="i-lucide-paw-print"
                            trailing
                        >
                            Schedule appointment
                        </UButton>
                    </div>
                </UForm>
            </UCard>
        </UContainer>
    </UApp>
</template>
