<script setup lang="ts">
import InputError from '@/components_old/InputError.vue';
import { Button } from '@/components_old/ui/button';
import { Input } from '@/components_old/ui/input';
import { Label } from '@/components_old/ui/label';
import SingleCardLayout from '@/layouts/SingleCardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

// TODO Update to match the new layout and components

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <SingleCardLayout
        page-title="Confirm password"
        card-title="Confirm your password"
        card-subtitle="This is a secure area of the application. Please confirm your password before continuing."
    >
        <form @submit.prevent="submit">
            <div class="space-y-6">
                <div class="grid gap-2">
                    <Label htmlFor="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        autofocus
                    />

                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Confirm Password
                    </Button>
                </div>
            </div>
        </form>
    </SingleCardLayout>
</template>
