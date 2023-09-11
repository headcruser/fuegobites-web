<script setup>
import {
    MDBCard,
    MDBCardBody,
    MDBCardTitle,
    MDBCardHeader,
MDBCardFooter,

} from "mdb-vue-ui-kit";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
  <AuthenticatedLayout>
      <div class="container-fluid p-4">
        <MDBCard>
            <MDBCardHeader>Acerca de </MDBCardHeader>
            <MDBCardBody>
                <MDBCardTitle>Fuego Bites</MDBCardTitle>
                <p class="lead mb-4">
                    Proyecto que integra las siguientes tecnologías
                </p>
                <ul class="list-unstyled">
                    <li class="mb-1"> <i class="fas fa-long-arrow-alt-right me-2 text-info"></i> Laravel 10</li>
                    <li class="mb-1"><i class="fas fa-long-arrow-alt-right me-2 text-info"></i> MDB</li>
                    <li class="mb-1"><i class="fas fa-long-arrow-alt-right me-2 text-info"></i> Inertia</li>
                    <li class="mb-1"><i class="fas fa-long-arrow-alt-right me-2 text-info"></i> Breeze</li>
                    <li class="mb-1"> <i class="fas fa-long-arrow-alt-right me-2 text-info"></i> Vuejs</li>
                </ul>



            </MDBCardBody>
            <MDBCardFooter >
                <p class="text-muted">
                    &copy; 2023 Desarrollado por <mark>Daniel Martinez Sierra</mark>
                </p>
            </MDBCardFooter>
        </MDBCard>
      </div>

  </AuthenticatedLayout>
</template>
