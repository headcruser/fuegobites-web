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
  MDBIcon,
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
        <div class="mt-4 mx-4">
            <MDBContainer>
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
                                        <MDBRow>
                                            <MDBCol class="col-12">
                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.name"
                                                        label="Nombre"
                                                        wrapperClass="mb-4"
                                                    />
                                                    <div v-if="form.errors.name" class="invalid-feedback">
                                                        {{ form.errors.name }}
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <MDBInput
                                                        type="text"
                                                        v-model="form.description"
                                                        label="Descripción"
                                                        wrapperClass="mb-4"
                                                    />

                                                    <div v-if="form.errors.description" class="invalid-feedback">
                                                        {{ form.errors.description }}
                                                    </div>
                                                </div>

                                                <MDBBtn color="primary" class="mb-2" type="submit" :disabled="form.processing"> Guardar </MDBBtn>

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
