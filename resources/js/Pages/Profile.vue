<script setup>

import {
    MDBCard,
    MDBCardBody,
    MDBCardHeader,
    MDBContainer,
    MDBInput,
} from "mdb-vue-ui-kit";

import { computed } from "vue";
import { usePage } from '@inertiajs/vue3'

import defaultImage from '@/img/default-image.png'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const roleNames = computed(() =>  usePage().props.auth.roles.join(''));

</script>

<template>
  <AuthenticatedLayout>
        <MDBContainer fluid class="p-5">
            <MDBCard>
                <MDBCardHeader class="py-3">
                <strong>Perfil del usuario</strong>
              </MDBCardHeader>
              <MDBCardBody text="center">
                  <form action="">
                      <div class="d-flex justify-content-center mb-4">
                        <img
                            class="rounded-circle shadow-1 mb-3 d-block"
                            :src="$page.props.auth.user?.photo ? `/storage/users/${$page.props.auth.user.id}/${$page.props.auth.user.photo}`: defaultImage"
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
              </MDBCardBody>
            </MDBCard>
        </MDBContainer>
  </AuthenticatedLayout>
</template>
