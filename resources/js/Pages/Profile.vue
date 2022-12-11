<script setup>
import { computed } from "vue";
import { usePage } from '@inertiajs/inertia-vue3'

import {
  MDBInput,
} from "mdb-vue-ui-kit";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const roleNames = computed(() =>  usePage().props.value.auth.roles.join(''));

</script>

<template>
  <AuthenticatedLayout>
    <section class="mb-10" style="background-color: #f3f7fa">
      <div class="container py-5">
        <div class="mb-3">
            <strong>Perfil</strong>
        </div>

        <form action="">
            <div class="d-flex justify-content-center mb-4">
              <img
                  class="rounded-circle shadow-1 mb-3 d-block"
                  src="../img/default-image.png"
                  alt="avatar"
                  style="width: 90px; margin: auto; display: block"
                />
            </div>

            <MDBInput
              type="text"
              label="Nombre"
              v-model="$page.props.auth.user.name"
              wrapperClass="mb-4"
              disabled
            />
            <MDBInput
              type="email"
              label="Correo"
              v-model="$page.props.auth.user.email"
              disabled
              wrapperClass="mb-4"
            />
            <MDBInput
              type="text"
              label="Roles"
              wrapperClass="mb-4"
              v-model="roleNames"
              disabled
            />
        </form>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
