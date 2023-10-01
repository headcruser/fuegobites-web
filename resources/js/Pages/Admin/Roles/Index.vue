<script setup>
import {
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBInput,
  MDBBtn,
  MDBIcon,
  MDBTable,
} from "mdb-vue-ui-kit";

import { ref, watch } from "vue";
import { Link ,router} from '@inertiajs/vue3'
import Swal from 'sweetalert2'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    roles: Object,
    filters: Object
});

// const form = useForm();
const search = ref(props.filters.search);
const perPage = ref(10);


const createDebounce = () =>  {
    let timeout = null;
    return function (fnc, delayMs) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
        fnc();
        }, delayMs || 500);
    };
}

watch(search,(value) => {
    const debouncer = createDebounce();

    debouncer(() => {
        router.get(route('admin.roles.index'),{
                search: value,
                perPage: perPage.value,
            },
            {
                preserveState:true,
                replace:true,
            }
        );
     })
})

const destroy = async (role)  => {

    if(role.name.includes('administrador')){
        return Swal.fire("Atención", "No puedes eliminar al administrador.", "error");
    }

    const result = await Swal.fire({
        title: '¿Deseas eliminar el registro?',
        text: "Se eliminara el registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No'
    })

    if (!result.isConfirmed) {
        return;
    }

    router.delete(route('admin.roles.destroy', role.id), {
        onSuccess: () => {
            Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success");
        },
    });
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="mt-4 mx-4">
            <MDBCard>
                <MDBCardHeader class="py-4 mb-3">
                    <h4 class="mb-0">Roles</h4>
                </MDBCardHeader>
            <MDBCardBody>
                <div class="d-flex justify-content-end mb-2">
                    <div class="d-flex">
                        <div class="form-outline">
                            <MDBInput v-model="search" label="Buscar"/>
                        </div>

                        <Link
                            as="button"
                            type="button"
                            className="btn btn-primary btn-sm ms-3 ripple-surface"
                            :href="route('admin.roles.create')"
                        >
                            <MDBIcon icon="plus"></MDBIcon>
                        </Link>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <MDBTable>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in roles.data" :key="role.id">
                                <td>{{ role.id }}</td>
                                <td>{{ role.name }}</td>
                                <td>{{ role.description }}</td>
                                <td class="text-nowrap">
                                    <Link
                                        tabIndex="1"
                                        className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                        as="button"
                                        :href="route('admin.roles.edit', role.id)"
                                    >
                                        <MDBIcon icon="pencil"></MDBIcon>
                                    </Link>

                                    <MDBBtn outline="danger"
                                        title="Eliminar"
                                        size="small"
                                        floating
                                        :disabled="role.name.includes('administrador')"
                                        @click="destroy(role)"
                                    >
                                        <MDBIcon  icon="trash"></MDBIcon>
                                    </MDBBtn>
                                </td>
                            </tr>
                        </tbody>
                    </MDBTable>
                </div>

                <Pagination class="mt-6" :links="roles.links" />

            </MDBCardBody>
            </MDBCard>
        </div>
    </AuthenticatedLayout>
</template>
