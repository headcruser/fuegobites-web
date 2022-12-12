<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { MDBBtn, MDBInput } from 'mdb-vue-ui-kit';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <div class="mb-4 text-lg text-gray-700">
            Restablecer contraseña.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">

            <MDBInput
                type="email"
                v-model="form.email"
                size="lg"
                autofocus
                autocomplete="username"
                label="Correo"
                wrapperClass="mb-4"
                :class="{
                    'is-invalid': form.errors.email != '' && form.hasErrors,
                }"
                :invalidFeedback="form.errors.email"
                required
            />

            <MDBInput
                type="password"
                size="lg"
                v-model="form.password"
                :class="{
                'is-invalid':
                    form.errors.password != '' && form.hasErrors,
                }"
                :invalidFeedback="form.errors.password"
                label="Contraseña"
                wrapperClass="mb-4"
                required
            />

            <MDBInput
                type="password"
                size="lg"
                v-model="form.password_confirmation"
                :class="{
                'is-invalid':
                    form.errors.password_confirmation != '' && form.hasErrors,
                }"
                :invalidFeedback="form.errors.password_confirmation"
                label="Confirmar contraseña"
                wrapperClass="mb-4"
                required
            />

             <!-- Submit button -->
            <MDBBtn
                color="primary"
                type="submit"
                block
                class="mb-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Reestablecer contraseña
            </MDBBtn>

        </form>
    </GuestLayout>
</template>
