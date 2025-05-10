<script setup lang="ts">
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Form } from '@nuxt/ui/runtime/types/form.d.ts';
import { computed, useTemplateRef } from 'vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const formRef = useTemplateRef<Form<any>>('formRef');
const formErrors = computed(() => Object.entries(form.errors).map(([name, message]) => ({ name, message })));

function showFormErrors() {
    formRef.value?.setErrors(formErrors.value);
}

const submitForm = () => {
    form.post(route('password.email'), {
        preserveScroll: true,
        onFinish() {
            showFormErrors();
        },
        onSuccess() {
            form.reset();
        },
    });
};
</script>

<template>
    <SingleCardLayout page-title="Forgot password" card-title="Forgot password" card-subtitle="Enter your email to receive a password reset link">
        <template #card>
            <UAlert v-if="status" :title="status" color="primary" variant="soft" class="-mt-2 mb-8" />

            <UForm ref="formRef" :state="form" @submit="submitForm">
                <UFormField name="email" label="Email Address" required>
                    <UInput
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="off"
                        v-model="form.email"
                        autofocus
                        placeholder="email@example.com"
                        class="w-full"
                    />
                </UFormField>

                <div class="mt-6 flex justify-center">
                    <UButton type="submit" :loading="form.processing" color="primary" size="lg" icon="i-lucide-paw-print" trailing>
                        Email password reset link
                    </UButton>
                </div>
            </UForm>
        </template>

        <template #footer>
            <UButton :href="route('login')" variant="ghost" size="sm">Login</UButton>
        </template>
    </SingleCardLayout>
</template>
