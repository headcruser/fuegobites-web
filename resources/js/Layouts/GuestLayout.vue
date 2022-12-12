<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
import {
  MDBContainer,
  MDBRow,
  MDBBtn,

  MDBCard,
  MDBCardBody,

  MDBCol,
  MDBCheckbox,
} from "mdb-vue-ui-kit";
</script>

<template>

<MDBContainer class="my-5 py-5 bg-light">
    <MDBRow class="d-flex justify-content-center">
      <MDBCol md="6">
        <MDBCard>
          <MDBCardBody class="p-4">
            <div class="flex flex-col sm:justify-center items-center mb-5">
                <Link href="/">
                    <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
                </Link>
            </div>

            <slot />

          </MDBCardBody>
        </MDBCard>
      </MDBCol>
    </MDBRow>
  </MDBContainer>
</template>
