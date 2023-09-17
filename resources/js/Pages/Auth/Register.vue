<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { MDBBtn, MDBInput } from 'mdb-vue-ui-kit';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <MDBInput
                    type="text"
                    v-model="form.name"
                    autofocus
                    autocomplete="username"
                    label="Nombre"
                    :class="{
                        'is-invalid': form.hasErrors && form.errors.name != undefined
                    }"
                    :invalidFeedback="form.errors.name"
                    required
                />
            </div>

            <div class="mt-4">
                <MDBInput
                    type="email"
                    v-model="form.email"
                    autocomplete="off"
                    label="Correo"
                    :class="{
                        'is-invalid': form.hasErrors && form.errors.email != undefined
                    }"
                    :invalidFeedback="form.errors.email"
                    required
                />

            </div>

            <div class="mt-4">
                <MDBInput
                    type="password"
                    v-model="form.password"
                    autocomplete="current-password"
                    label="Contraseña"
                    :class="{
                        'is-invalid': form.hasErrors && form.errors.password != undefined
                    }"
                    :invalidFeedback="form.errors.password"
                    required
                />

            </div>

            <div class="mt-4">
                <MDBInput
                    type="password"
                    v-model="form.password_confirmation"
                    autocomplete="current-password"
                    label="Confirmar contraseña"
                    :class="{
                        'is-invalid': form.hasErrors && form.errors.password_confirmation != undefined
                    }"
                    :invalidFeedback="form.errors.password_confirmation"
                    required
                />
            </div>

            <div class="d-flex justify-content-center mt-4">
                <Link :href="route('login')">
                    ¿Ya estas registrado ?
                </Link>

                <MDBBtn
                    color="primary"
                    type="submit"
                    block
                    class="mb-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Iniciar Sesión
                </MDBBtn>
            </div>
        </form>
    </GuestLayout>
</template>
