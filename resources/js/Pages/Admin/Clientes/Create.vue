<script setup>
import {
  MDBCol,
  MDBRow,
  MDBContainer,
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBInput,
  MDBBtn,
  MDBBreadcrumb,
  MDBBreadcrumbItem
} from "mdb-vue-ui-kit";


import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({});

const form = useForm({
    nombre:'',
    email: '',
    telefono_fijo:'',
    celular:'',
    direccion:'',
    ciudad:'',
    estado:'',
    observaciones: '',
})

const submit = () => {
    form.transform((data) => ({
        ...data,
    }))|

    form.post(route('admin.clientes.store'))
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-3 mx-4">
            <MDBContainer fluid>
                <MDBBreadcrumb>
                    <MDBBreadcrumbItem><a :href="route('dashboard')">Inicio</a></MDBBreadcrumbItem>
                    <MDBBreadcrumbItem ><a :href="route('admin.clientes.index')">Clientes</a></MDBBreadcrumbItem>
                    <MDBBreadcrumbItem active><a :href="route('admin.clientes.create')">Crear cliente</a></MDBBreadcrumbItem>
                </MDBBreadcrumb>

                <!--Section: Content-->
                <section>
                    <MDBRow>
                        <MDBCol md="12">
                            <MDBCard class="mb-4">
                                <MDBCardHeader class="py-3">
                                    <strong>Crear Cliente</strong>
                                </MDBCardHeader>
                                <MDBCardBody>
                                    <form @submit.prevent="submit">
                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-6">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.nombre"
                                                        label="Nombre"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.nombre" class="invalid-feedback">
                                                        {{ form.errors.nombre }}
                                                    </div>
                                                </div>
                                            </MDBCol>

                                            <MDBCol class="col-6">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.telefono_fijo"
                                                        label="Telefono Fijo"
                                                        wrapperClass="mb-4"
                                                        :helper="'Ejemplo: 4613585890'"
                                                    />
                                                    <div v-if="form.errors.telefono_fijo" class="invalid-feedback">
                                                        {{ form.errors.telefono_fijo }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        v-model.trim="form.email"
                                                        label="Correo"
                                                        type="email"
                                                        wrapperClass="mb-4"
                                                        :helper="'Ejemplo: mi-correo@gmail.com'"
                                                    />
                                                    <div v-if="form.errors.email" class="invalid-feedback">
                                                        {{ form.errors.email }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        v-model.trim="form.celular"
                                                        label="Celular"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.celular" class="invalid-feedback">
                                                        {{ form.errors.celular }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        v-model.trim="form.direccion"
                                                        label="Direccion"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.direccion" class="invalid-feedback">
                                                        {{ form.errors.direccion }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        v-model.trim="form.ciudad"
                                                        label="Ciudad"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.ciudad" class="invalid-feedback">
                                                        {{ form.errors.ciudad }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        v-model.trim="form.estado"
                                                        label="Estado"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.estado" class="invalid-feedback">
                                                        {{ form.errors.estado }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.observaciones"
                                                        label="Observaciones"
                                                        wrapperClass="mb-4"
                                                    />

                                                    <div v-if="form.errors.observaciones" class="invalid-feedback">
                                                        {{ form.errors.observaciones }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBBtn color="primary" class="mb-2" type="submit" :disabled="form.processing"> Guardar </MDBBtn>
                                    </form>
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

<style>
    .form-helper{
        color: #757575 !important;
    }
</style>
