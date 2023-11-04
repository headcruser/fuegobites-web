<script setup>
import {
  MDBCol,
  MDBRow,
  MDBContainer,
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBTable,
  MDBBreadcrumb,
  MDBBreadcrumbItem
} from "mdb-vue-ui-kit";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    cliente:Object
});
</script>

<template>
    <AuthenticatedLayout>

        <div class="mt-3 mx-4">
            <MDBContainer fluid>
                <nav aria-label="breadcrumb">
                    <MDBBreadcrumb>
                        <MDBBreadcrumbItem><a :href="route('dashboard')">Inicio</a></MDBBreadcrumbItem>
                        <MDBBreadcrumbItem><a :href="route('admin.clientes.index')">Clientes</a></MDBBreadcrumbItem>
                        <MDBBreadcrumbItem active><a :href="route('admin.clientes.show',cliente.id)">Panel</a></MDBBreadcrumbItem>
                    </MDBBreadcrumb>
                </nav>
                <!--Section: Content-->
                <section>
                    <MDBRow>
                        <MDBCol md="12">
                            <MDBCard class="mb-4">
                                <MDBCardHeader class="py-3">
                                    <strong>Panel cliente</strong>
                                </MDBCardHeader>
                                <MDBCardBody>
                                    <MDBTable bordered>
                                        <tbody>
                                            <tr>
                                                <td class="bg-primary text-white fw-bold">NOMBRE</td>
                                                <td class="text-uppercase">{{ cliente.nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-primary text-white fw-bold">TELEFONO FIJO</td>
                                                <td class="text-uppercase">{{ cliente.telefono_fijo }}</td>
                                            </tr>
                                            <tr >
                                                <td class="bg-primary text-white fw-bold">CELULAR</td>
                                                <td class="text-uppercase">{{ cliente.celular }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-primary text-white fw-bold">CIUDAD</td>
                                                <td class="text-uppercase">{{ cliente.ciudad }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-primary text-white fw-bold">ESTADO</td>
                                                <td class="text-uppercase">{{ cliente.estado }}</td>
                                            </tr>
                                            <tr>
                                                <td class="bg-primary text-white fw-bold">OBSERVACIONES</td>
                                                <td class="text-uppercase">{{ cliente.observaciones }}</td>
                                            </tr>
                                        </tbody>
                                    </MDBTable>
                                </MDBCardBody>
                            </MDBCard>
                        </MDBCol>
                    </MDBRow>
                </section>
                <!--Section: Content-->
            </MDBContainer>
        </div>
    </AuthenticatedLayout>
</template>
