<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';

import { useForm } from '@inertiajs/inertia-vue3';
import { MDBBtn, MDBInput } from 'mdb-vue-ui-kit';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-lg text-gray-700">
            Restablecer contraseña.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" novalidate>

            <MDBInput
                type="email"
                v-model="form.email"
                size="lg"
                autofocus
                autocomplete="username"
                label="Correo"
                wrapperClass="mb-5"
                :class="{
                    'is-invalid': form.errors.email != '' && form.hasErrors,
                }"
                :invalidFeedback="form.errors.email"
                required
            />

            <MDBBtn
                color="primary"
                type="submit"
                block
                class="mb-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Enviar enlace para restablecer contraseña
            </MDBBtn>
        </form>
    </GuestLayout>
</template>
