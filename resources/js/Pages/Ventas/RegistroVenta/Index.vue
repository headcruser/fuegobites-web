<script setup>
import {
    MDBBtn,
    MDBModal,
    MDBModalHeader,
    MDBModalTitle,
    MDBModalBody,
    MDBModalFooter,
    MDBInput,
    MDBListGroup,
    MDBListGroupItem,
    MDBSwitch,
    MDBRow,
    MDBCol,
    MDBDatepicker,
    MDBSelect,
} from "mdb-vue-ui-kit";

import { computed, onMounted, ref } from "vue";
import { useForm, router} from "@inertiajs/vue3";

import _ from "lodash";

import moment from "moment";
import 'moment/dist/locale/es-mx'

import Swal from "sweetalert2";

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Editor from 'primevue/editor';
import Sidebar from 'primevue/sidebar';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onBeforeMount } from "vue";

const props = defineProps({
    ventas: Object
});

const FORMAS_PAGO = {
    EFECTIVO: 'Efectivo',
    TRANSFERENCIA: 'Transferencia'
}

const selectPago = computed(() => {
    return Object.entries(FORMAS_PAGO).map( ([key,forma]) => ({text: forma,value:forma}));
})

const totalPedidos = computed(() =>  Intl.NumberFormat().format(_.sumBy(props.ventas,(venta) => Number(venta.total)) ))

const totalCantidad = computed(() =>  Intl.NumberFormat().format(_.sumBy(props.ventas,(venta) => Number(venta.cantidad)) ))

const vFechaEntrega = {
    beforeMount: (el,venta) => {
        const fecha = moment(venta.value.fecha);
        const fechaActual = moment();

        el.innerHTML = `Entrega ${fecha.calendar()} `

        if (fecha.isBefore(fechaActual)) {
            el.classList.remove('text-muted');
            el.classList.add('text-danger');
        }
    }
}

onBeforeMount(() => {
    moment.locale('es-mx', {
        calendar : {
            lastDay : '[Ayer] DD/MMMM/YYYY',
            sameDay : '[Hoy] DD/MMMM/YYYY',
            nextDay : '[Mañana] DD/MMMM/YYYY',
            lastWeek : '[Semana pasada] DD/MMMM/YYYY',
            nextWeek : 'DD/MMMM/YYYY',
            sameElse : 'L'
        }
    });
})

onMounted(() => {
    form.fecha = moment().format('DD/MM/YYYY');
})

const modalAgregarPedido = ref(false);

const form = useForm({
    nombre:'',
    descripcion:'',
    total:'',
    cantidad: '',
    fecha: '',
    forma_pago: '',
    pagado:false,
    formas_pago:[...selectPago.value]
});

const formEdit = useForm({
    id:'',
    nombre:'',
    descripcion:'',
    total:'',
    cantidad: '',
    fecha: '',
    forma_pago: '',
    pagado:false,
    formas_pago:[...selectPago.value]
});

const visibleRight = ref(false);


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
            modalAgregarPedido.value = false;
        },
    });
}

const evtClickActualizar = () => {
    updateEdit();
}

const evtClickPagado = async (venta) => {
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
}

const updateEdit = async () => {
    // formEdit.transform((data) => ({
    //     ...data,
    // }));

    formEdit.put(route('ventas.registro.update',formEdit.id),{
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
                        <h3 class="fw-lighter">Total: <strong> $ {{ totalPedidos }} </strong></h3>
                    </div>
                </MDBListGroupItem>
            </MDBListGroup>


            <p class="fw-lighter">{{ props.ventas.length }} Pedidos / {{ totalCantidad }} Unidades </p>

            <MDBListGroup light class="pb-3">
                <MDBListGroupItem
                    class="d-flex justify-content-between align-items-center"
                    v-for="(venta)  in props.ventas"
                    :key="venta.id"
                    >
                    <div class="d-flex align-items-center">
                        <div>
                            <i
                              class="fa-lg fas fa-trash-alt me-2 text-danger"
                              title="Eliminar"
                              @click="evtClickEliminar(venta)"
                              ></i>
                            <i
                              class="fa-lg"
                              :class="{'far fa-circle':!venta.pagado ,'fa fa-circle-check' : venta.pagado}"
                              @click="evtClickPagado(venta)"
                              ></i>
                        </div>
                        <div class="ms-3" @click="evtClickVenta(venta)">
                            <p class="fw-bold mb-1">{{ venta.id }} <i class="fas fa-long-arrow-alt-right"></i> {{ venta.nombre }}</p>
                            <p class="text-muted mb-0" v-fecha-entrega="venta"></p>
                        </div>
                    </div>
                    <div>
                        <small class="fw-bolder">$  {{ venta.total }}</small>
                    </div>
                </MDBListGroupItem>
            </MDBListGroup>
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
            <MDBModalTitle id="modalAgregarPedidoLabel"> Registro de pedidos </MDBModalTitle>
        </MDBModalHeader>
        <MDBModalBody>

            <div class="form-group mb-4">
                <MDBInput
                    class="active"
                    type="text"
                    v-model="form.nombre"
                    :class="{'is-invalid':form.errors.nombre}"
                    :invalid-feedback="form.errors.nombre"
                    label="Nombre"
                />
            </div>


            <MDBRow class="mb-4">
                <MDBCol col="12" lg="6">
                    <div class="form-group">
                        <MDBInput
                            type="number"
                            v-model="form.cantidad"
                            :class="{'is-invalid':form.errors.cantidad}"
                            :invalid-feedback="form.errors.cantidad"
                            class="active"
                            label="Cantidad"
                            title="Cantidad"
                        />
                    </div>
                </MDBCol>
                <MDBCol col="12" lg="6">
                    <div class="form-group">
                        <MDBInput
                            type="number"
                            v-model="form.total"
                            class="active"
                            :class="{'is-invalid':form.errors.total}"
                            :invalid-feedback="form.errors.total"
                            label="Precio"
                            title="Precio"
                        />
                    </div>
                </MDBCol>
            </MDBRow>

            <MDBRow class="mb-4">
                <MDBCol col="12">
                    <MDBDatepicker
                        v-model="form.fecha"
                        :class="{'is-invalid':form.errors.fecha}"
                        :invalid-feedback="form.errors.fecha"
                        label="Fecha entrega"
                        title="Fecha entrega"
                        :monthsFull="[
                        'Enero',
                        'Febrero',
                        'Marzo',
                        'Abril',
                        'Mayo',
                        'Junio',
                        'Julio',
                        'Agosto',
                        'Septiembre',
                        'Octubre',
                        'Noviembre',
                        'Diciembre'
                        ]"
                        :monthsShort="[
                        'Ene',
                        'Feb',
                        'Mar',
                        'Abr',
                        'May',
                        'Jun',
                        'Jul',
                        'Ago',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dic'
                        ]"
                        :weekdaysFull="[
                        'Domingo',
                        'Lunes',
                        'Martes',
                        'Miercoles',
                        'Jueves',
                        'Viernes',
                        'Sabado'
                        ]"
                        :weekdaysShort="['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']"
                        :weekdaysNarrow="['D', 'L', 'M', 'M', 'J', 'V', 'S']"
                        okBtnText="Ok"
                        clearBtnText="Limpiar"
                        cancelBtnText="Cancelar"
                    />
                </MDBCol>
            </MDBRow>


            <div class="form-group mb-4">
                <label class="form-label m-0 p-0 small"> Descripción</label>
                <Editor v-model="form.descripcion" editorStyle="height: 320px" />
            </div>

            <div class="form-group mb-4">
                <MDBSwitch
                    label="Pagado"
                    v-model="form.pagado"
                />
            </div>

            <div class="form-group mb-4" v-if="form.pagado">
                <MDBSelect
                    searchPlaceholder="Buscar"
                    noResultsText="Sin Resultados"
                    label="Forma de pago"
                    class="col-12"
                    :preselect="false"
                    :class="{'is-invalid':form.errors.forma_pago}"
                    :invalid-feedback="form.errors.forma_pago"
                    v-model:selected="form.forma_pago"
                    v-model:options="form.formas_pago"
                    filter
                />
            </div>

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
      :baseZIndex="-1"
      style="width: 50rem;">

        <h4>Detalle del pedido</h4>
        <p class="text-muted">{{ formEdit.id }} - {{ formEdit.nombre }}</p>


        <div class="form-group">
            <MDBInput
                class="active"
                type="text"
                v-model="formEdit.nombre"
                :class="{'is-invalid':formEdit.errors.nombre}"
                :invalid-feedback="formEdit.errors.nombre"
                label="Nombre"
                wrapperClass="mb-4"
            />
        </div>

        <MDBRow class="mb-3">
            <MDBCol col="12" lg="6">
                <div class="form-group">
                    <MDBInput
                        type="number"
                        v-model="formEdit.cantidad"
                        :class="{'is-invalid':formEdit.errors.cantidad}"
                        :invalid-feedback="formEdit.errors.cantidad"
                        class="active"
                        label="Cantidad"
                        title="Cantidad"
                    />
                </div>
            </MDBCol>

            <MDBCol col="12" lg="6">
                <div class="form-group">
                    <MDBInput
                        type="number"
                        v-model="formEdit.total"
                        class="active"
                        :class="{'is-invalid':formEdit.errors.total}"
                        :invalid-feedback="formEdit.errors.total"
                        label="Precio"
                        title="Precio"
                    />
                </div>
            </MDBCol>
        </MDBRow>

        <MDBRow class="mb-3">
            <MDBCol col="12">
                <div class="form-group">
                    <MDBDatepicker
                        v-model="formEdit.fecha"
                        :class="{'is-invalid':formEdit.errors.fecha}"
                        :invalid-feedback="formEdit.errors.fecha"
                        label="Fecha Entrega"
                        title="Fecha Entrega"
                        :monthsFull="[
                        'Enero',
                        'Febrero',
                        'Marzo',
                        'Abril',
                        'Mayo',
                        'Junio',
                        'Julio',
                        'Agosto',
                        'Septiembre',
                        'Octubre',
                        'Noviembre',
                        'Diciembre'
                        ]"
                        :monthsShort="[
                        'Ene',
                        'Feb',
                        'Mar',
                        'Abr',
                        'May',
                        'Jun',
                        'Jul',
                        'Ago',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dic'
                        ]"
                        :weekdaysFull="[
                        'Domingo',
                        'Lunes',
                        'Martes',
                        'Miercoles',
                        'Jueves',
                        'Viernes',
                        'Sabado'
                        ]"
                        :weekdaysShort="['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']"
                        :weekdaysNarrow="['D', 'L', 'M', 'M', 'J', 'V', 'S']"
                        okBtnText="Ok"
                        clearBtnText="Limpiar"
                        cancelBtnText="Cancelar"
                    />
                </div>
            </MDBCol>
        </MDBRow>

        <div class="form-group mb-3">
            <label class="form-label m-0 p-0 small"> Descripción</label>
            <Editor v-model="formEdit.descripcion" editorStyle="height: 150px" />
        </div>

        <div class="form-group mb-4">
            <MDBSwitch
                label="Pagado"
                v-model="formEdit.pagado"
            />
        </div>

        <div class="form-group mb-4" v-if="formEdit.pagado">
            <MDBSelect
                searchPlaceholder="Buscar"
                noResultsText="Sin Resultados"
                label="Forma de pago"
                class="col-12"
                :preselect="false"
                :class="{'is-invalid':formEdit.errors.forma_pago}"
                :invalid-feedback="formEdit.errors.forma_pago"
                v-model:selected="formEdit.forma_pago"
                v-model:options="formEdit.formas_pago"
                filter
            />
        </div>

        <MDBBtn color="primary" @click="evtClickActualizar"  :disabled="formEdit.processing">Guardar</MDBBtn>

    </Sidebar>
  </AuthenticatedLayout>
</template>

<style>
    .datepicker-toggle-button{
        right: 0;
    }
</style>
