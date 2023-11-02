<script setup>
import {
    MDBBtn,
    MDBModal,
    MDBModalHeader,
    MDBModalTitle,
    MDBModalBody,
    MDBModalFooter,
    MDBListGroup,
    MDBListGroupItem,
    MDBSelect,
} from "mdb-vue-ui-kit";

import { computed, onMounted, ref } from "vue";
import { useForm, router} from "@inertiajs/vue3";


import _ from "lodash";

import moment from "moment";
import 'moment/dist/locale/es-mx'

import Swal from "sweetalert2";


import vFechaEntrega from '@/Directives/Calendar'

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Sidebar from 'primevue/sidebar';

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from '@/Components/Pagination.vue';
import FormVenta from './Components/FormVenta.vue'
import defaultProducto  from '@/img/default-product.png';

import { onBeforeMount } from "vue";
import { reactive } from "vue";

const props = defineProps({
    ventas: Object,
    productos: Object,
});

const FORMAS_PAGO = {
    EFECTIVO: 'Efectivo',
    TRANSFERENCIA: 'Transferencia'
}

const selectPago = computed(() => {
    return Object.entries(FORMAS_PAGO).map( ([key,forma]) => ({text: forma,value:forma}));
})

const selectProductos = computed(() => {
    return  props.productos.map(producto => {
        return {
            ...producto,
            text: producto.nombre,
            value: producto.id,
            secondaryText: `${producto.codigo} - ${producto.precio}`
        }
    })
})

onBeforeMount(() => {
    moment.locale('es-mx', {
        calendar : {
            lastDay : '[Ayer] DD/MMM/YYYY',
            sameDay : '[Hoy] DD/MMM/YYYY',
            nextDay : '[Mañana] DD/MMM/YYYY',
            lastWeek : '[Semana pasada] DD/MMM/YYYY',
            nextWeek : 'DD/MMM/YYYY',
            sameElse : 'L'
        }
    });
})

onMounted(() => {
    form.productos_options = [...selectProductos.value];
})

const modalAgregarPedido = ref(false);

const form = useForm({
    nombre:'',
    descripcion:'',

    fecha: '',

    forma_pago: '',
    formas_pago:[...selectPago.value],

    producto: '',
    productos_options:[],

    detalles_ventas: [],
    pagado:false,
});

const formEdit = useForm({
    id:'',
    nombre:'',
    descripcion:'',
    fecha: '',

    forma_pago: '',
    formas_pago:[...selectPago.value],

    producto: '',
    productos_options:[],

    detalles_ventas: [],

    pagado:false,

});

const visibleRight = ref(false);

const selectFiltroPagado = reactive({
    value : '',
    options: [
        {text:'Pagado',value:1},
        {text:'Sin pagar',value:0}
    ],
    selected: computed(() => {
        return selectFiltroPagado.options.filter(option => option['selected']).map(option => option.value)
    })
})

const evtChangeFilter = async () => {
    router.get(route("ventas.registro.index"),
      {
        pagado: selectFiltroPagado.selected,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
}

const evtClickVenta = (venta) => {
    readEdit(venta);

    visibleRight.value = true;
}

const evtClickEliminar = async (venta) => {
    const result = await Swal.fire({
        title: `¿Deseas eliminar la venta?`,
        html: `Se eliminara la venta <strong>${venta.id} - ${venta.nombre}</strong>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
    })

    if (!result.isConfirmed) {
        return;
    }

    router.delete(route("ventas.registro.destroy", venta.id), {
        preserveScroll: true,
        onSuccess: () => {
        },
    });
}

const evtClickRegistrar = () => {
    form.transform((data) => ({
        ...data,
    }));

    form.post(route('ventas.registro.store'),{
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            form.productos_options = [...selectProductos.value];
            modalAgregarPedido.value = false;
        },
    });
}

const evtClickActualizar = () => {
    updateEdit();
}

const evtClickPagado = async (venta) => {

    if (venta.pagado) {
        return;
    }

    const result = await Swal.fire({
        title: `¿Deseas marcar como el pedido como pagado?`,
        html: `Se pagará ${venta.total} a <strong>${venta.id} - ${venta.nombre}</strong>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Pagado",
        cancelButtonText: "Cancelar",
    })

    if (!result.isConfirmed) {
        return;
    }

    readEdit(venta);

    // MARCAR COMO PAGADO EL PEDIDO
    formEdit.pagado = true;
    formEdit.forma_pago = FORMAS_PAGO.EFECTIVO;

    updateEdit();
}

const readEdit = (venta) => {
    formEdit.id = venta.id
    formEdit.nombre = venta.nombre;
    formEdit.descripcion = venta.descripcion;
    formEdit.cantidad = venta.cantidad;
    formEdit.total = venta.total;
    formEdit.fecha = moment(venta.fecha).format('DD/MM/YYYY');
    formEdit.pagado = venta.pagado;
    formEdit.forma_pago = venta.forma_pago;

    if(venta.pagado) {
        const findFormaPago = formEdit.formas_pago.find( option => option['value'] == venta.forma_pago);

        if (findFormaPago) {
            findFormaPago['selected'] = true;
        }
    } else {
        formEdit.formas_pago.forEach( option => option['selected'] = false);
    }

    formEdit.productos_options = [...selectProductos.value];

    formEdit.detalles_ventas = venta.detalles.map(detalle => {
       return {
            id: detalle.id,
            id_producto: detalle.producto.id,
            nombre:detalle.nombre,
            descripcion: detalle.descripcion,
            precio: detalle.precio,
            cantidad: detalle.cantidad,
            imagen: detalle.producto.imagen ?? defaultProducto,
            total: detalle.total
       }
    })
}

const updateEdit = async () => {

    formEdit.transform((data) => ({
        ...data,
        _method: 'put',
    }))

    formEdit.post(route('ventas.registro.update',formEdit.id),{
        preserveScroll: true,
        onSuccess: () => {
            formEdit.reset();
            visibleRight.value = false;
        },
    });
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-3 mx-3">
        <h4 class="mb-0">Ventas</h4>

        <section class="pt-3">
            <MDBListGroup light class="mb-3">
                <MDBListGroupItem>
                    <div class="d-flex justify-content-between align-content-center" >
                        <div>
                            <MDBBtn @click="modalAgregarPedido=true">Agregar pedido</MDBBtn>
                        </div>
                        <h3 class="fw-lighter">Total: <strong> $ {{ Intl.NumberFormat().format(_.sumBy(props.ventas.data,(venta) => Number(venta.total)) ) }} </strong></h3>
                    </div>
                </MDBListGroupItem>
            </MDBListGroup>

            <div class="d-flex justify-content-between">
                <p class="fw-lighter">{{ props.ventas.total }} Pedidos / {{ Intl.NumberFormat().format(_.sumBy(props.ventas.data,(venta) => Number(venta.cantidad)) ) }} Unidades </p>
                <div>
                    <MDBSelect
                        searchPlaceholder="Buscar"
                        noResultsText="Sin Resultados"
                        selectAllLabel="Seleccionar todos"
                        label="Filtrar por"
                        size="sm"
                        class="col-12"
                        :preselect="false"
                        v-model:selected="selectFiltroPagado.value"
                        v-model:options="selectFiltroPagado.options"
                        multiple
                        @change="evtChangeFilter"
                    />
                </div>

            </div>

            <MDBListGroup light class="pb-3">
                <MDBListGroupItem
                    v-for="(venta)  in props.ventas.data"
                    :key="venta.id"
                    >
                    <div class="d-flex justify-content-between align-content-center" :class="{'text-decoration-line-through':venta.pagado}">
                        <div class="d-flex align-items-center">
                            <div class="text-center" style="min-width: 3rem;">
                                <i
                                  class="fa-lg fas fa-trash-alt me-2 text-danger"
                                  title="Eliminar"
                                  @click="evtClickEliminar(venta)"
                                  ></i>
                                <i
                                  class="fa-lg"
                                  :class="{'far fa-circle':!venta.pagado ,'fa fa-circle-check text-success' : venta.pagado}"
                                  @click="evtClickPagado(venta)"
                                  ></i>
                            </div>
                            <div class="ms-3" @click="evtClickVenta(venta)">

                                <p class="fw-bold mb-1 text-uppercase">{{ venta.nombre }} </p>
                                <p class="mb-0" v-fecha-entrega="venta"></p>
                            </div>
                        </div>

                        <div class="me-2 text-end">
                            <small class="fw-bolder text-nowrap">$  {{ venta.total }}</small>
                            <div class="text-nowrap">Total {{ venta.cantidad }}</div>
                        </div>
                    </div>

                </MDBListGroupItem>
            </MDBListGroup>

            <Pagination class="mt-6" :links="props.ventas.links" />
        </section>

    </div>

    <MDBModal
        id="modalAgregarPedido"
        tabindex="-1"
        labelledby="modalAgregarPedidoLabel"
        v-model="modalAgregarPedido"
        size="lg"
        staticBackdrop
    >
        <MDBModalHeader>
            <MDBModalTitle id="modalAgregarPedidoLabel"> Registrar pedido</MDBModalTitle>
        </MDBModalHeader>
        <MDBModalBody>
            <FormVenta
                v-model:nombre="form.nombre"
                v-model:nombre_errors="form.errors.nombre"

                v-model:producto="form.producto"
                v-model:productos_options="form.productos_options"
                v-model:productos_errors="form.errors.producto"

                v-model:fecha="form.fecha"
                v-model:fecha_errors="form.errors.fecha"

                v-model:descripcion="form.descripcion"
                v-model:descripcion_errors="form.errors.descripcion"

                v-model:forma_pago="form.forma_pago"
                v-model:formas_pago="form.formas_pago"

                v-model:pagado="form.pagado"
                v-model:detalles_ventas="form.detalles_ventas"
            />
        </MDBModalBody>
        <MDBModalFooter>
            <MDBBtn color="secondary" @click="modalAgregarPedido = false">Cerrar</MDBBtn>
            <MDBBtn color="primary" @click="evtClickRegistrar"  :disabled="form.processing">Registrar</MDBBtn>
        </MDBModalFooter>
    </MDBModal>

    <Sidebar
      v-model:visible="visibleRight"
      position="right"
      class="bg-white"
      :baseZIndex="1064"
      style="width: 50rem;">

        <h4>Detalle del pedido</h4>
        <p class="text-muted">{{ formEdit.id }} - {{ formEdit.nombre }}</p>


        <FormVenta
            v-model:nombre="formEdit.nombre"
            v-model:nombre_errors="formEdit.errors.nombre"

            v-model:producto="formEdit.producto"
            v-model:productos_options="formEdit.productos_options"
            v-model:productos_errors="formEdit.errors.producto"

            v-model:fecha="formEdit.fecha"
            v-model:fecha_errors="formEdit.errors.fecha"

            v-model:descripcion="formEdit.descripcion"
            v-model:descripcion_errors="formEdit.errors.descripcion"

            v-model:forma_pago="formEdit.forma_pago"
            v-model:formas_pago="formEdit.formas_pago"

            v-model:pagado="formEdit.pagado"
            v-model:detalles_ventas="formEdit.detalles_ventas"
        />

        <MDBBtn color="primary" @click="evtClickActualizar"  :disabled="formEdit.processing">Guardar</MDBBtn>

    </Sidebar>
  </AuthenticatedLayout>
</template>

<style>
    .datepicker-toggle-button{
        right: 0;
    }
</style>
