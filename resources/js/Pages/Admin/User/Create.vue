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

import { ref } from "vue";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm} from "@inertiajs/vue3";

const props = defineProps({
    roles: Object,
});

const checkedValues = ref(Object.entries(props.roles).map( option => {
    return {label: option[1],  id: option[0], checked: false};
}))

const form = useForm({
    name:'',
    email:'',
    password: '',
    photo: '',
    enviar_datos:false
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        roles: checkedValues.value.filter(option => option.checked).map(option => option.id),
    }))

    form.post(route('admin.users.store'),{
        onSuccess: () => {
            console.log('ok');
        },
    })
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-3 mx-3">
            <MDBContainer fluid>
                <!--Section: Content-->
                <section>
                    <MDBRow>
                    <MDBCol md="12" class="mb-4 mb-md-0">
                        <MDBCard class="mb-4">
                        <MDBCardHeader class="py-3">
                            <strong>Crear usuario</strong>
                        </MDBCardHeader>
                        <MDBCardBody>
                            <form @submit.prevent="submit">
                                <MDBRow>
                                    <MDBCol class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <strong>Foto</strong>
                                        </div>

                                        <div class="d-flex justify-content-center mb-4 border-1">
                                            <MDBFileUpload
                                                :defaultFiles="[]"
                                                defaultMsg="Arrastre y suelte un archivo"
                                                class="shadow-1"
                                                disabledRemoveBtn
                                                @input="form.photo = $event.target.files[0]"
                                            />
                                        </div>

                                        <div class="form-group pb-3">
                                            <MDBInput
                                                type="text"
                                                v-model="form.name"
                                                label="Nombre"
                                                :class="{'is-invalid':form.errors.name}"
                                                :invalid-feedback="form.errors.name"
                                                autocomplete="off"
                                            />
                                        </div>

                                        <div class="form-group pb-3">
                                            <MDBInput
                                                type="email"
                                                v-model="form.email"
                                                label="Correo"
                                                autocomplete="off"
                                                :class="{'is-invalid':form.errors.email}"
                                                :invalid-feedback="form.errors.email"
                                            />
                                        </div>

                                        <div class="form-group pb-3">
                                            <MDBInput
                                                type="password"
                                                v-model="form.password"
                                                label="Contraseña"
                                                autocomplete="off"
                                                aria-autocomplete="off"
                                                :class="{'is-invalid':form.errors.password}"
                                                :invalid-feedback="form.errors.password"
                                            />
                                        </div>

                                        <div class="form-group mb-3">
                                            <MDBCheckbox :label="'Enviar datos por correo'" v-model="form.enviar_datos"/>
                                        </div>

                                        <MDBBtn
                                          color="primary"
                                          class="btn-block"
                                          type="submit"
                                          :disabled="form.processing"> Guardar </MDBBtn>

                                    </MDBCol>

                                    <MDBCol class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <strong>Roles</strong>
                                        </div>
                                        <div>
                                            <template v-for="(option,key) in checkedValues" :key="key">
                                                <MDBCheckbox :label="option.label" :value="option.id" v-model="option.checked"/>
                                            </template>
                                            <!-- <mdb-input v-for="option in options" type="checkbox" :id="option.label" v-model="option.checked" :key="option.id" :label="option.label" >{{option.checked}}</mdb-input> -->
                                        </div>
                                    </MDBCol>
                                </MDBRow>
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
