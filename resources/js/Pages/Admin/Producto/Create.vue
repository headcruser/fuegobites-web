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
} from "mdb-vue-ui-kit";

import { MDBFileUpload } from "mdb-vue-file-upload";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";


const props = defineProps({});

const form = useForm({
    nombre:'',
    codigo:'',
    descripcion:'',
    precio:'',
    imagen: '',
})

const submit = () => {
    form.transform((data) => ({
        ...data,
    }))

    form.post(route('admin.productos.store'))
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-3 mx-4">
            <MDBContainer fluid>
                <!--Section: Content-->
                <section>
                    <MDBRow>
                        <MDBCol md="12">
                            <MDBCard class="mb-4">
                                <MDBCardHeader class="py-3">
                                    <strong>Crear producto</strong>
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
                                                        v-model="form.codigo"
                                                        label="Codigo"
                                                        wrapperClass="mb-4"
                                                        :helper="'Ejemplo: VL'"
                                                    />
                                                    <div v-if="form.errors.codigo" class="invalid-feedback">
                                                        {{ form.errors.codigo }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="number"
                                                        v-model="form.precio"
                                                        label="Precio"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.precio" class="invalid-feedback">
                                                        {{ form.errors.precio }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.descripcion"
                                                        label="Descripción "
                                                        wrapperClass="mb-4"
                                                    />

                                                    <div v-if="form.errors.descripcion" class="invalid-feedback">
                                                        {{ form.errors.descripcion }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="d-flex justify-content-center mb-4 border-1">
                                                    <MDBFileUpload
                                                        :defaultFiles="[]"
                                                        defaultMsg="Arrastre y suelte un archivo"
                                                        class="shadow-1"
                                                        disabledRemoveBtn
                                                        @input="form.imagen = $event.target.files[0]"
                                                    />
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
