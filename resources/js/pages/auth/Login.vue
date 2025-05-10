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
    password: '',
    remember: false,
});

const formRef = useTemplateRef<Form<any>>('formRef');
const formErrors = computed(() => Object.entries(form.errors).map(([name, message]) => ({ name, message })));

function showFormErrors() {
    formRef.value?.setErrors(formErrors.value);
}

const submitForm = () => {
    form.post(route('login'), {
        preserveScroll: true,
        onFinish() {
            showFormErrors();
        },
    });
};
</script>

<template>
    <SingleCardLayout page-title="Login" card-title="Log in to your account" card-subtitle="Enter your email and password below to log in">
        <template #card>
            <UAlert v-if="status" :title="status" color="primary" variant="soft" class="-mt-2 mb-8" />

            <UForm ref="formRef" :state="form" @submit="submitForm" class="space-y-4">
                <UFormField name="email" label="Email Address" required>
                    <UInput
                        id="email"
                        type="email"
                        autofocus
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                        class="w-full"
                    />
                </UFormField>

                <UFormField name="password" label="Password" required>
                    <UInput
                        id="password"
                        type="password"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Password"
                        class="w-full"
                    />
                </UFormField>

                <UFormField name="remember">
                    <UCheckbox id="remember" name="remember" v-model="form.remember" label="Remember me" />
                </UFormField>

                <div class="mt-6 flex justify-center">
                    <UButton type="submit" :loading="form.processing" color="primary" size="lg" icon="i-lucide-paw-print" trailing>Log in</UButton>
                </div>
            </UForm>
        </template>

        <template #footer>
            <UButton :href="route('password.request')" variant="ghost" size="sm">Forgot password?</UButton>
        </template>
    </SingleCardLayout>
</template>
