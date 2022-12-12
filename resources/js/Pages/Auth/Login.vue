<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

import { MDBRow, MDBCol, MDBBtn, MDBInput, MDBCheckbox, MDBIcon } from "mdb-vue-ui-kit";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <GuestLayout>
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit" novalidate>
      <!-- Email input -->
      <MDBInput
        type="email"
        v-model="form.email"
        size="lg"
        autofocus
        autocomplete="username"
        label="Correo"
        wrapperClass="mb-5"
        :class="{
          'is-invalid': form.errors.email != '' && form.email == '' && form.hasErrors,
        }"
        :invalidFeedback="form.errors.email"
        required
      />
      <!-- Password input -->
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

      <!-- 2 column grid layout -->
      <MDBRow class="mb-5 pt-3">
        <MDBCol md="6" class="d-flex justify-content-center">
          <!-- Checkbox -->
          <MDBCheckbox
            label="Recordarme"
            v-model:checked="form.remember"
            wrapperClass="mb-3 mb-md-0"
            checked
          />
        </MDBCol>

        <MDBCol md="6" class="d-flex justify-content-center">
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
