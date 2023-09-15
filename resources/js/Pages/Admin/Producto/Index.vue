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
import defaultProducto  from '@/img/default-product.png';

const props = defineProps({
    productos: Object,
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
        // router.get(route('admin.productos.index'),{
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

const destroy = async (producto)  => {
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

    router.delete(route('admin.productos.destroy', producto.id), {
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
                    <h4 class="mb-0">Productos</h4>
                </MDBCardHeader>
            <MDBCardBody>
                <div class="d-flex justify-content-end mb-4">
                    <div class="form-outline">
                        <MDBInput v-model="search" label="Buscar"/>
                    </div>

                    <Link
                        as="button"
                        type="button"
                        className="btn btn-primary btn-sm ms-3 ripple-surface"
                        :href="route('admin.productos.create')"
                    >
                        <MDBIcon icon="plus"></MDBIcon>
                    </Link>
                </div>

                <MDBTable>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in productos.data" :key="producto.id">
                            <td>{{ producto.id }}</td>
                            <td>{{ producto.codigo }}</td>
                            <td>{{ producto.nombre }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img
                                      :src="producto.imagen ?? defaultProducto"
                                      :alt="`producto_${producto.id}`"
                                      style="width: 45px;height: 45px;">
                                </div>
                            </td>

                            <td>{{ producto.precio }}</td>
                            <td>
                                <Link
                                    tabIndex="1"
                                    className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                                    as="button"
                                    :href="route('admin.productos.edit', producto.id)"
                                >
                                    <MDBIcon icon="pencil"></MDBIcon>
                                </Link>

                                <MDBBtn outline="danger"
                                    title="Eliminar"
                                    size="small"
                                    floating
                                    @click="destroy(producto)"
                                >
                                    <MDBIcon  icon="trash"></MDBIcon>
                                </MDBBtn>
                            </td>
                        </tr>
                    </tbody>
                </MDBTable>

                <Pagination class="mt-6" :links="productos.links" />

            </MDBCardBody>
            </MDBCard>
        </div>
    </AuthenticatedLayout>
</template>
