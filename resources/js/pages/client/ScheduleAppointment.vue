<script setup lang="ts">
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { usePage } from '@inertiajs/vue3';
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
            age_years: null,
            age_months: null,
        },
        appointment: {
            preferred_date: '',
            preferred_time: [],
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

const submitted = ref(false);

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
        onSuccess: () => {
            submitted.value = true;
            form.reset();
        },
    });
}

function createAnimalType(item: string) {
    animalTypes.value.push(item);
    form.animal.type = item;
}
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
                <UForm
                    ref="formRef"
                    :state="form"
                    :validate="validateForm"
                    :validate-on="['change']"
                    :validate-on-input-delay="0"
                    :disabled="form.processing"
                    @submit.prevent="submitForm"
                    class="space-y-4"
                >
                    <!-- Client -->
                    <p class="mb-2 text-2xl/12">About you</p>

                    <!--Client: name -->
                    <UFormField label="Name" name="client.name" class="w-full" required>
                        <UInput v-model="form.client.name" placeholder="Your name" class="w-full" />
                    </UFormField>
                    <!--Client: email-->
                    <UFormField label="Email" name="client.email" description="Used to contact you about your appointment" class="w-full" required>
                        <UInput v-model="form.client.email" type="email" placeholder="Your email" class="w-full" />
                    </UFormField>

                    <USeparator class="mb-0 py-6" />

                    <!-- Pet -->
                    <p class="mb-2 pt-0 text-2xl/12">About your pet</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <!-- Pet: name-->
                        <UFormField label="Name" name="animal.name" class="w-full" required>
                            <UInput v-model="form.animal.name" class="w-full" placeholder="Your pet's name" />
                        </UFormField>
                        <!-- Pet: type -->
                        <UFormField label="Type" name="animal.type" class="w-full" required>
                            <UInputMenu
                                v-model="form.animal.type"
                                data-input-field-name="animal.type"
                                placeholder="Your pet type"
                                create-item="always"
                                :items="animalTypes"
                                @create="createAnimalType"
                                class="w-full"
                            />
                        </UFormField>
                    </div>

                    <!-- Pet: age -->
                    <UFormField label="Age" required>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <UFormField name="animal.age_years">
                                <div class="flex w-full gap-2 sm:items-center">
                                    <UInput v-model="form.animal.age_years" placeholder="Age in years..." class="w-full" />
                                    <span class="whitespace-nowrap text-gray-400">year(s) and</span>
                                </div>
                            </UFormField>
                            <UFormField name="animal.age_months">
                                <div class="flex w-full items-center gap-2">
                                    <UInput v-model="form.animal.age_months" placeholder="... and months" class="w-full" />
                                    <span class="whitespace-nowrap text-gray-400">month(s)</span>
                                </div>
                            </UFormField>
                        </div>
                    </UFormField>

                    <USeparator class="mb-0 py-6" />

                    <!-- Appointment -->
                    <p class="mb-2 pt-0 text-2xl/12">About your visit</p>
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="space-y-2 md:w-1/3">
                            <!-- Appointment: date + time -->
                            <UFormField label="Preferred Time" name="appointment.preferred_date" class="w-full" required>
                                <UInput v-model="form.appointment.preferred_date" type="date" class="w-full" />
                            </UFormField>
                            <UFormField name="appointment.preferred_time">
                                <UCheckboxGroup v-model="form.appointment.preferred_time" orientation="horizontal" :items="timeOfDay" />
                            </UFormField>
                        </div>
                        <!-- Appointment: symptoms-->
                        <UFormField label="Symptoms" name="appointment.symptoms" class="w-full md:w-2/3" required>
                            <UTextarea v-model="form.appointment.symptoms" placeholder="Why are you visiting us?" :rows="4" class="w-full" />
                        </UFormField>
                    </div>

                    <!-- Form: controls -->
                    <div class="mt-12 flex justify-center">
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
