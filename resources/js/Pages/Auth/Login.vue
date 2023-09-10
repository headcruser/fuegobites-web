<script setup>
import { useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

import { MDBRow, MDBCol, MDBBtn, MDBInput, MDBCheckbox } from "mdb-vue-ui-kit";
import { ref } from "vue";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const vVisiblePassword = ref(false);

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
}

const f_ShowPassword = () => {
  vVisiblePassword.value = !vVisiblePassword.value;
}

console.log(form);
</script>

<template>
  <GuestLayout>
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit" novalidate>
        <MDBRow class="pb-4">
            <MDBCol col="12">
                <MDBInput
                    type="email"
                    v-model="form.email"
                    size="lg"
                    autofocus
                    autocomplete="username"
                    label="Correo"
                    :class="{
                    'is-invalid': form.hasErrors && form.errors.email != undefined
                    }"
                    :invalidFeedback="form.errors.email"
                    required
                />
            </MDBCol>
        </MDBRow>

        <MDBRow class="pb-4">
            <MDBCol col="12">
                <div class="d-flex align-content-center  align-items-center">
                    <MDBInput
                        :type="vVisiblePassword ? 'text': 'password'"
                        wrapperClass="w-100"
                        size="lg"
                        v-model="form.password"
                        :class="{
                        'is-invalid':form.hasErrors && (form.errors.password != undefined )
                        }"
                        :helper="form.errors.password"
                        label="Contraseña"
                        required
                    />
                    <div>
                        <i
                        class="fas fa-lg ms-3"
                        :title="vVisiblePassword ? 'Ocultar contraseña': 'Mostrar contraseña'"
                        :class="{'fa-eye':vVisiblePassword,'fa-eye-slash':!vVisiblePassword}"
                        @click="f_ShowPassword"></i>
                    </div>
                </div>
            </MDBCol>
        </MDBRow>
        <!-- Password input -->


        <MDBRow class="mb-4 pt-2">
            <MDBCol md="6">
            <!-- Checkbox -->
            <MDBCheckbox
                label="Recordarme"
                v-model:checked="form.remember"
                wrapperClass="mb-3 mb-md-0"
                checked
            />
            </MDBCol>

            <MDBCol md="6" class="d-flex justify-content-end">
            <!-- Simple link -->
            <a :href="route('password.request')">¿Olvidaste tu contraseña?</a>
            </MDBCol>
        </MDBRow>

        <!-- Submit button -->
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
    </form>
  </GuestLayout>
</template>

<style>
    .form-helper{
        color: #dc4c64 !important;
    }

    .was-validated .form-control:invalid, .form-control.is-invalid[type="password"]{
        margin-bottom: 0.25rem;
    }
</style>
