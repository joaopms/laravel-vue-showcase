<script setup lang="ts">
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Form } from '@nuxt/ui/runtime/types/form';
import { computed, useTemplateRef } from 'vue';

interface Props {
    token: string;
    email: string;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,

    password: '',
    password_confirmation: '',
});

const formRef = useTemplateRef<Form<any>>('formRef');
const formErrors = computed(() => Object.entries(form.errors).map(([name, message]) => ({ name, message })));

function showFormErrors() {
    formRef.value?.setErrors(formErrors.value);
}

const submitForm = () => {
    form.post(route('password.store'), {
        preserveScroll: true,
        onFinish() {
            showFormErrors();
        },
        onSuccess() {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <SingleCardLayout page-title="Reset password" card-title="Reset password" card-subtitle="Please enter your new password below">
        <template #card>
            <UForm ref="formRef" :state="form" @submit="submitForm" class="space-y-4">
                <UFormField name="password" label="New Password" required>
                    <UInput
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="password"
                        v-model="form.password"
                        class="w-full"
                        autofocus
                        placeholder="New password"
                    />
                </UFormField>

                <UFormField name="password_confirmation" label="New Password" required>
                    <UInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="password"
                        v-model="form.password_confirmation"
                        class="w-full"
                        autofocus
                        placeholder="Confirm new password"
                    />
                </UFormField>

                <div class="mt-6 flex justify-center">
                    <UButton type="submit" :loading="form.processing" color="primary" size="lg" icon="i-lucide-paw-print" trailing>
                        Reset password
                    </UButton>
                </div>
            </UForm>
        </template>

        <template #footer>
            <UButton :href="route('login')" variant="ghost" size="sm">Login</UButton>
        </template>
    </SingleCardLayout>
</template>
