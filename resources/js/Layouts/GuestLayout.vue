<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

import {
    MDBContainer,
    MDBRow,
    MDBCol,
    MDBCard,
    MDBCardBody,
} from "mdb-vue-ui-kit";

import { Link } from '@inertiajs/vue3'
</script>

<template>

<MDBContainer class="my-5 py-5 bg-light">
    <MDBRow class="d-flex justify-content-center">
      <MDBCol md="6">
        <MDBCard>
          <MDBCardBody>
            <div class="pb-4 text-center">
                <Link href="/" >
                    <ApplicationLogo />
                </Link>
            </div>

            <slot />

          </MDBCardBody>
        </MDBCard>
      </MDBCol>
    </MDBRow>
  </MDBContainer>
</template>
