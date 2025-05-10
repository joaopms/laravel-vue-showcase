<script setup lang="ts">
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submitForm = () => {
    form.post(route('verification.send'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <SingleCardLayout
        page-title="Email verification"
        card-title="Verify email"
        card-subtitle="Please verify your email address by clicking on the link we just emailed to you."
    >
        <template #card>
            <UAlert
                v-if="status === 'verification-link-sent'"
                title="A new verification link has been sent to the email address you provided during registration."
                color="primary"
                variant="soft"
                class="-mt-2 mb-8"
            />

            <form @submit.prevent="submitForm" class="text-center">
                <p>Didn't receive the email?</p>

                <UButton type="submit" :loading="form.processing" color="primary" size="lg" icon="i-lucide-paw-print" trailing class="mt-4">
                    Resend verification email
                </UButton>
            </form>
        </template>

        <template #footer>
            <UButton :href="route('logout')" method="post" variant="ghost" size="sm">Log out</UButton>
        </template>
    </SingleCardLayout>
</template>
