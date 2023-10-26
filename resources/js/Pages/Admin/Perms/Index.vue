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
    perms: Object,
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
        router.get(route('admin.perms.index'),{
                search: value,
                perPage: perPage.value,
            },
            {
                preserveState:false,
                replace:true,
            }
        );
     })
})

const destroy = async (role)  => {
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

    router.delete(route('admin.perms.destroy', role.id), {
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
                    <h4 class="mb-0">Permisos</h4>
                </MDBCardHeader>
            <MDBCardBody>
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <Link
                            as="button"
                            type="button"
                            className="btn btn-default btn-sm ms-3 ripple-surface"
                            :href="route('admin.perms.panel')"
                        >
                            <MDBIcon icon="landmark"></MDBIcon> PANEL PERMISOS
                        </Link>
                    </div>
                    <div class="d-flex">
                        <div class="form-outline">
                            <MDBInput v-model="search" label="Buscar"/>
                        </div>

                        <Link
                            as="button"
                            type="button"
                            className="btn btn-primary btn-sm ms-3 ripple-surface"
                            :href="route('admin.perms.create')"
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
                            <tr v-for="perm in perms.data" :key="perm.id">
                                <td>{{ perm.id }}</td>
                                <td>{{ perm.name }}</td>
                                <td>{{ perm.description }}</td>
                                <td class="text-nowrap">
                                    <Link
                                        tabIndex="1"
                                        className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                        as="button"
                                        :href="route('admin.perms.edit', perm.id)"
                                    >
                                        <MDBIcon icon="pencil"></MDBIcon>
                                    </Link>

                                    <MDBBtn outline="danger"
                                        title="Eliminar"
                                        size="small"
                                        floating
                                        :disabled="perm.name.includes('administrador')"
                                        @click="destroy(perm)"
                                    >
                                        <MDBIcon  icon="trash"></MDBIcon>
                                    </MDBBtn>
                                </td>
                            </tr>
                        </tbody>
                    </MDBTable>
                </div>

                <Pagination class="mt-6" :links="perms.links" />

            </MDBCardBody>
            </MDBCard>
        </div>
    </AuthenticatedLayout>
</template>
