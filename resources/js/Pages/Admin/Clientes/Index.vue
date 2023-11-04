<script setup>
import {
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBInput,
  MDBBtn,
  MDBIcon,
  MDBTable,
  MDBBreadcrumb,
  MDBBreadcrumbItem
} from "mdb-vue-ui-kit";

import { ref, watch } from "vue";
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    clientes: Object,
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
        // router.get(route('admin.clientes.index'),{
        //         search: value,
        //         perPage: perPage.value,
        //     },
        //     {
        //         preserveState:true,
        //         replace:true,
        //     }
        // );
     })
})

const destroy = async (cliente)  => {
    const result = await Swal.fire({
        title: '¿Deseas eliminar el registro?',
        text: "Se eliminara el registro!",
        icon: 'warning',
        allowOutsideClick:false,
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No'
    })

    if (!result.isConfirmed) {
        return;
    }

    router.delete(route('admin.clientes.destroy', cliente.id), {
        onSuccess: () => {
            Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success");
        },
    });
}

</script>

<template>
    <AuthenticatedLayout>

        <div class="mt-4 mx-4">
            <MDBBreadcrumb>
                <MDBBreadcrumbItem><a :href="route('dashboard')">Inicio</a></MDBBreadcrumbItem>
                <MDBBreadcrumbItem ><a :href="route('admin.clientes.index')">Clientes</a></MDBBreadcrumbItem>
            </MDBBreadcrumb>
            <MDBCard>
                <MDBCardHeader class="py-4 mb-3">
                    <h4 class="mb-0">Clientes</h4>
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
                            :href="route('admin.clientes.create')"
                        >
                            <MDBIcon icon="plus"></MDBIcon>
                        </Link>
                    </div>
                </div>

                <hr>


                <MDBTable align="middle" responsive>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">DIRECCION</th>
                            <th scope="col">TELEFONO FIJO</th>
                            <th scope="col">CELULAR</th>
                            <th scope="col">CIUDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in clientes.data" :key="cliente.id">
                            <td>{{ cliente.id }}</td>

                            <td>
                                {{ cliente.nombre }}
                            </td>

                            <td>
                                <p class="fw-normal mb-1">{{ cliente.direccion }}</p>
                                <p class="text-muted mb-0">{{ cliente.ciudad }}</p>
                            </td>
                            <td>
                                {{ cliente.telefono_fijo }}
                            </td>
                            <td>
                                {{ cliente.celular }}
                            </td>


                            <td class="text-nowrap">
                                <Link
                                    tabIndex="1"
                                    className="btn btn-small btn-outline-info btn-floating ripple-surface"
                                    as="button"
                                    :href="route('admin.clientes.show', cliente.id)"
                                >
                                    <MDBIcon icon="id-card"></MDBIcon>
                                </Link>

                                <Link
                                    tabIndex="1"
                                    className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                    as="button"
                                    :href="route('admin.clientes.edit', cliente.id)"
                                >
                                    <MDBIcon icon="pencil"></MDBIcon>
                                </Link>

                                <MDBBtn outline="danger"
                                    title="Eliminar"
                                    size="small"
                                    floating
                                    @click="destroy(cliente)"
                                >
                                    <MDBIcon  icon="trash"></MDBIcon>
                                </MDBBtn>
                            </td>
                        </tr>
                    </tbody>
                </MDBTable>


                <Pagination class="mt-6" :links="clientes.links" />

            </MDBCardBody>
            </MDBCard>
        </div>
    </AuthenticatedLayout>
</template>
