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
    MDBCheckbox,
} from "mdb-vue-ui-kit";

import { MDBFileUpload } from "mdb-vue-file-upload";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    producto: Object,
});

const form = useForm({
    nombre: props.producto.nombre,
    codigo: props.producto.codigo,
    descripcion: props.producto.descripcion,
    precio: props.producto.precio,
    imagen: '',
    remover_imagen:false,
    preparado:props.producto.preparado,
    visible: props.producto.visible,
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
    }))

    form.post(route('admin.productos.update', props.producto.id), {})
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-4 mx-4">
            <MDBContainer fluid>
                <!--Section: Content-->
                <section>
                    <MDBRow>
                        <MDBCol md="12" class="mb-4 mb-md-0">
                            <MDBCard class="mb-4">
                                <MDBCardHeader class="py-3">
                                    <strong>Editar producto {{ props.producto.nombre }}</strong>
                                </MDBCardHeader>
                                <MDBCardBody>
                                    <form @submit.prevent="submit">
                                        <div class="d-flex justify-content-center">
                                            <img
                                                v-if="props.producto.imagen"
                                                class="rounded-circle shadow-1 mb-3"
                                                :src="`${props.producto.imagen}`"
                                                alt="avatar"
                                                style="width: 50px;height: 45px;"
                                            />
                                        </div>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-6">
                                                <div class="form-group">
                                                    <MDBInput type="text" v-model="form.nombre" label="Nombre"
                                                        wrapperClass="mb-4" />
                                                    <div v-if="form.errors.nombre" class="invalid-feedback">
                                                        {{ form.errors.nombre }}
                                                    </div>
                                                </div>
                                            </MDBCol>

                                            <MDBCol class="col-6">
                                                <div class="form-group">
                                                    <MDBInput type="text" v-model="form.codigo" label="Codigo"
                                                        wrapperClass="mb-4" :helper="'Ejemplo: VL'" />
                                                    <div v-if="form.errors.codigo" class="invalid-feedback">
                                                        {{ form.errors.codigo }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput type="number" v-model="form.precio" label="Precio"
                                                        wrapperClass="mb-4" />
                                                    <div v-if="form.errors.precio" class="invalid-feedback">
                                                        {{ form.errors.precio }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput type="text" v-model="form.descripcion" label="Descripción "
                                                        wrapperClass="mb-4" />

                                                    <div v-if="form.errors.descripcion" class="invalid-feedback">
                                                        {{ form.errors.descripcion }}
                                                    </div>
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-3">
                                            <MDBCol>
                                                <MDBCheckbox label="¿Visible en página principal?" inline v-model="form.visible"/>
                                                <MDBCheckbox label="¿Es preparado?" inline  v-model="form.preparado"/>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow>
                                            <MDBCol class="col-12">
                                                <div class="d-flex justify-content-center mb-4 border-1">
                                                    <MDBFileUpload :defaultFiles="[]"
                                                        defaultMsg="Arrastre y suelte un archivo" class="shadow-1"
                                                        disabledRemoveBtn @input="form.imagen = $event.target.files[0]; form.remover_imagen = false" />
                                                </div>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBRow class="pb-4">
                                            <MDBCol class="col-12">
                                                <MDBCheckbox label="¿Eliminar imagen actual?" class="" :value="1" v-model="form.remover_imagen"/>
                                            </MDBCol>
                                        </MDBRow>

                                        <MDBBtn color="primary" class="mb-2" type="submit" :disabled="form.processing">
                                            Guardar </MDBBtn>
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
