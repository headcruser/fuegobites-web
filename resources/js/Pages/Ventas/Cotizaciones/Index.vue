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
import { Link, router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';


const props = defineProps({
    cotizaciones: Object,
    filters: Object
});

const search = ref(props?.filters?.search);
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
        router.get(route('ventas.cotizaciones.index'),{
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

const destroy = async (cotizacion)  => {
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

    router.delete(route('ventas.cotizaciones.destroy', cotizacion.id), {
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
                    <h4 class="mb-0">Cotizaciones</h4>
                </MDBCardHeader>
            <MDBCardBody>
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <Link
                            as="button"
                            type="button"
                            className="btn btn-primary ripple-surface"
                            :href="route('ventas.cotizaciones.create')"
                        >
                            <MDBIcon icon="plus"/> CREAR
                        </Link>
                    </div>
                    <div class="d-flex">

                        <div class="form-outline">
                            <MDBInput v-model="search" label="Buscar"/>
                        </div>
                        <MDBBtn class="ms-2">
                            <MDBIcon icon="filter"/>
                        </MDBBtn>

                    </div>
                </div>

                <hr>


                <MDBTable align="middle" size="xs" responsive border>
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col">#</th>
                            <th scope="col">CLIENTE</th>
                            <th scope="col">VENDEDOR</th>
                            <th scope="col">FECHA</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cotizaciones.data.length == 0">
                            <td colspan="7" class="text-center text-muted"> Sin Registros</td>
                        </tr>

                        <tr v-if="cotizaciones.data.length > 0" v-for="cotizacion in cotizaciones.data" :key="cotizacion.id" class="text-uppercase">
                            <td>{{ cotizacion.id }}</td>
                            <td >
                                {{ cotizacion.cliente.nombre }}
                            </td>
                            <td>
                                {{ cotizacion.vendedor.name }}
                            </td>
                            <td class="text-center">
                                {{ cotizacion.fecha }}
                            </td>

                            <td>
                                <div class="d-flex justify-content-between">
                                    <span>$</span>
                                    <span>{{ cotizacion.total }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ cotizacion.status }}</span>
                            </td>
                            <td class="text-nowrap">
                                <Link
                                    tabIndex="1"
                                    className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                    as="button"
                                    :href="route('ventas.cotizaciones.edit', cotizacion.id)"
                                >
                                    <MDBIcon icon="pencil"></MDBIcon>
                                </Link>

                                <MDBBtn outline="danger"
                                    title="Eliminar"
                                    size="small"
                                    floating
                                    @click="destroy(cotizacion)"
                                >
                                    <MDBIcon  icon="trash"></MDBIcon>
                                </MDBBtn>
                            </td>
                        </tr>
                    </tbody>
                </MDBTable>


                <Pagination class="mt-6" :links="cotizaciones.links" />

            </MDBCardBody>
            </MDBCard>
        </div>
    </AuthenticatedLayout>
</template>
