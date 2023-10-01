<script setup>
import {
    MDBCard,
    MDBCardBody,
    MDBCardFooter,
    MDBInput,
    MDBBtn,
    MDBIcon,
    MDBRow,
    MDBCol,
    MDBCardTitle,
    MDBCardText,
} from "mdb-vue-ui-kit";

import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";

import Swal from "sweetalert2";

import defaultImage from '@/img/default-image.png'

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    users: Object,
    filters: Object,
});


const search = ref(props.filters.search);
const perPage = ref(10);

const createDebounce = () => {
    let timeout = null;
    return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fnc();
        }, delayMs || 500);
    };
};

watch(search, (value) => {
    const debouncer = createDebounce();

    debouncer(() => {
        router.get(
            route("admin.users.index"),
            {
                search: value,
                perPage: perPage.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    });
});

const destroy = async (user) => {

    if (user?.name_role_user?.includes('administrador')) {
        return Swal.fire("Atención", "no puedes eliminar al administrador", "error");
    }

    const result = await Swal.fire({
        title: `¿Deseas eliminar al usuario ${user.name}?`,
        text: "Se eliminara el registro!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No",
    })

    if (!result.isConfirmed) {
        return;
    }

    router.delete(route("admin.users.destroy", id), {
        onSuccess: () => {
            Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success");
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-4 mx-4">
            <h4 class="mb-0">Usuarios</h4>

            <div class="d-flex justify-content-end mt-2 mb-2">
                <div class="d-flex">
                    <div class="form-outline">
                        <MDBInput v-model="search" label="Buscar">
                        </MDBInput>
                    </div>

                    <Link
                      as="button"
                      type="button"
                      className="btn btn-primary btn-sm ms-3 ripple-surface"
                      :href="route('admin.users.create')">

                        <MDBIcon icon="plus"></MDBIcon>
                    </Link>
                </div>
            </div>

            <MDBRow class="pt-2">
                <template v-for="user in users.data" :key="user.id">
                    <MDBCol sm="4" class="pb-4">
                        <MDBCard>
                            <MDBCardBody class="text-center">
                                <img class="rounded-circle shadow-1 mb-3 d-block"
                                    :src="user.photo ? `/storage/users/${user.id}/${user.photo}` : defaultImage" alt="avatar"
                                    style="width: 45px;height: 45px; margin: auto; display: block" />

                                <MDBCardTitle>{{ user.name }}</MDBCardTitle>

                                <MDBCardText>
                                    <a :href="`mailto:${user.email}`">{{ user.email }}</a>
                                    <p>{{ user.name_role_user || 'Sin Rol' }}</p>
                                </MDBCardText>
                            </MDBCardBody>
                            <MDBCardFooter class="d-flex justify-content-center">
                                <Link tabIndex="1" className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                    as="button" :href="route('admin.users.edit', user.id)">
                                <MDBIcon icon="pencil"></MDBIcon>
                                </Link>

                                <MDBBtn outline="danger" :disabled="user.name_role_user == 'administrador' && user.id == 1"
                                    title="Eliminar" size="small" floating @click="destroy(user)">
                                    <MDBIcon icon="trash"></MDBIcon>
                                </MDBBtn>
                            </MDBCardFooter>
                        </MDBCard>
                    </MDBCol>
                </template>
            </MDBRow>
        </div>
    </AuthenticatedLayout>
</template>
