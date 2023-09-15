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
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    roles: Object,
    user:Object,
});



const checkedValues = ref(Object.entries(props.roles).map( option => {
    const isChecked = props.user.roles.some( role => role.id == option[0]);
    return {label: option[1],  id: option[0], checked: isChecked};
}))

const form = useForm({
    name:props.user.name,
    email: props.user.email,
    password: '',
    photo: '',
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'put',
        roles: checkedValues.value.filter(option => option.checked).map(option => option.id),
    }))

    form.post(route('admin.users.update',props.user.id),{})
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-4 mx-4">
            <MDBContainer>
                <!--Section: Content-->
                <section>
                    <MDBRow>
                    <MDBCol md="12" class="mb-4 mb-md-0">
                        <MDBCard class="mb-4">
                        <MDBCardHeader class="py-3">
                            <strong>Editar usuario</strong>
                        </MDBCardHeader>
                        <MDBCardBody>
                            <form @submit.prevent="submit">
                                <MDBRow>
                                    <MDBCol class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <strong>Foto</strong>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <img
                                                v-if="user.photo"
                                                class="rounded-circle shadow-1 mb-3"
                                                :src="`/storage/users/${user.id}/${user.photo}`"
                                                alt="avatar"
                                                style="width: 50px"
                                            />
                                        </div>

                                        <div class="d-flex justify-content-center mb-4 border-1">
                                            <MDBFileUpload
                                                :defaultFiles="[
                                                ]"
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
                                            />
                                        </div>

                                        <div class="form-group pb-3">
                                            <MDBInput
                                                type="email"
                                                v-model="form.email"
                                                label="Correo"
                                                :class="{'is-invalid':form.errors.email}"
                                                :invalid-feedback="form.errors.email"
                                            />
                                        </div>

                                        <div class="form-group pb-5">
                                            <MDBInput
                                                type="password"
                                                v-model="form.password"
                                                label="Contraseña"
                                                aria-autocomplete="off"
                                                helper="Escribir solo si deseas cambiar la contraseña"
                                                :class="{'is-invalid':form.errors.name}"
                                                :invalid-feedback="form.errors.name"
                                            />
                                        </div>

                                        <MDBBtn
                                          color="primary"
                                          block
                                          class="mb-2"
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
