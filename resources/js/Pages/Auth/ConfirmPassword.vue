<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    })
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirmar contraseña" />

        <div class="mb-4 text-sm text-muted">
            Esta es un area segura de la aplicación. Por favor confirme su contraseña para continuar.
        </div>

        <form @submit.prevent="submit">
            <div class="pb-4">
                <MDBInput
                    type="password"
                    v-model="form.password"
                    :class="{
                        'is-invalid': form.hasErrors && form.errors.password != undefined
                    }"
                    :invalidFeedback="form.errors.password"
                    label="Correo"
                    required
                    autocomplete="current-password"
                    autofocus
                />
            </div>

            <div>
                <MDBBtn
                    color="primary"
                    type="submit"
                    block
                    class="mb-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Confirmar
                </MDBBtn>
            </div>
        </form>
    </GuestLayout>
</template>
