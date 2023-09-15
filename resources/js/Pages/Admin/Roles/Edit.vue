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

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    role: Object,
});

const form = useForm({
    name: props.role.name,
    description: props.role.description,
})

const submit = () => {
    form.transform((data) => ({
        ...data
    }));

    form.put(route('admin.roles.update',props.role.id));
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
                                    <strong>Editar Rol</strong>
                                </MDBCardHeader>
                                <MDBCardBody text="center">
                                    <form @submit.prevent="submit">

                                        <div class="form-group pb-4">
                                            <MDBInput
                                                type="text"
                                                v-model="form.name"
                                                label="Nombre"
                                                :class="{'is-invalid':form.errors.name}"
                                                :invalid-feedback="form.errors.name"
                                            />
                                        </div>

                                        <div class="form-group pb-4">
                                            <MDBInput
                                                type="text"
                                                v-model="form.description"
                                                label="Descripción"
                                                :class="{'is-invalid':form.errors.description}"
                                                :invalid-feedback="form.errors.description"
                                            />
                                        </div>

                                        <MDBBtn
                                            block
                                            color="primary"
                                            class="mb-2"
                                            type="submit"
                                            :disabled="form.processing"> Guardar </MDBBtn>
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
