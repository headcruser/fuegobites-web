<script setup>
import {
MDBBtn,
MDBListGroup,
MDBListGroupItem,
} from "mdb-vue-ui-kit";

import _ from "lodash";

import moment from "moment";
import 'moment/dist/locale/es-mx'

import Swal from "sweetalert2";

import vFechaEntrega from '@/Directives/Calendar'
import ClipboardJS from 'clipboard'

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from '@/Components/Pagination.vue';

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Calendar from 'primevue/calendar';


import { ref,computed, onBeforeMount, onMounted } from "vue";
import { router} from "@inertiajs/vue3";

const props = defineProps({
    pedidos: Object,
    totales: Object,
    fecha_inicio: String,
    fecha_fin: String
});

const dates = ref();

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
    dates.value = [
        new Date(props.fecha_inicio),
        new Date(props.fecha_fin),
    ];
})

const totalUnidades = computed(() => _.sumBy(props.totales,(total) => Number(total.cantidad)))

const clipboard  = new ClipboardJS('.btn-copy', {
    text: function () {
        return  props.totales.map( total => `${total.codigo} ${total.cantidad}`).join(' \n');
    },
});

clipboard.on('success', function (e) {
    Swal.fire({
        icon: 'success',
        title: 'Copiado correctamente',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
})

const onClickFilterDate =  () => {
    const [fecha_inicio,fecha_fin] = dates.value;

    if (fecha_inicio) {
        const mfecha_inicio = moment(fecha_inicio).format('YYYY-MM-DD');
        const mfecha_fin = moment(fecha_fin ?? fecha_inicio).format('YYYY-MM-DD');

        router.get(route("ventas.pedidos.index"),
          {
            fecha_inicio: mfecha_inicio,
            fecha_fin: mfecha_fin,
          },
          {
            preserveState: true,
            replace: true,
          }
        );
    }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-3 mx-3">
        <h4 class="mb-0">Pedidos pendientes</h4>

        <div class="my-2">
            <div class="row">
                <div class="col-md-3 mb-4"  v-for="total in props.totales " >
                    <div class="card card-input">
                        <div class="card-body">
                            <p class="text-uppercase fw-bold text-muted">{{ total.nombre }}</p>
                            <p class="h2 fw-bold">{{ total.cantidad }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-content-center justify-content-between mb-3">

            <Calendar v-model="dates" showIcon selectionMode="range" :manualInput="false" >
                <template #footer>
                    <div class="m-2">
                        <MDBBtn color="primary" size="sm" @click="onClickFilterDate">Filtrar</MDBBtn>
                    </div>
                </template>
            </Calendar>

            <p class="fw-lighter">
                <i class="far fa-clone fa-lg me-2 btn-copy" v-if="props.pedidos.data.length > 0" title="Copiar pedidos"></i>
                <span>{{ totalUnidades }}  Vasos</span>
            </p>
        </div>

        <div v-if="props.pedidos.data.length > 0">
            <MDBListGroup light class="pb-3">
                <MDBListGroupItem
                    v-for="(pedido)  in  props.pedidos.data"
                    :key="pedido.id"
                    >
                    <div class="d-flex justify-content-between align-content-center" :class="{'text-decoration-line-through':pedido.pagado}">
                        <div class="d-flex align-items-center">
                            <div class="text-center" style="min-width: 3rem;">
                                <i class="fa-lg far fa-circle"></i>
                            </div>
                            <div class="ms-3">
                                <p class="fw-bold mb-1 text-uppercase">{{ pedido.nombre }} </p>
                                <p class="text-muted mb-0" >{{ pedido.venta.nombre }}</p>
                            </div>
                        </div>

                        <div class="me-2 text-end">
                            <small class="fw-bolder text-nowrap">Cantidad {{ pedido.cantidad }}</small>
                            <div class="text-nowrap" v-fecha-entrega="pedido.venta"></div>
                        </div>
                    </div>

                </MDBListGroupItem>
            </MDBListGroup>

            <Pagination class="mt-6" :links="props.pedidos.links" />
        </div>
        <div v-else>
            <p class="text-muted text-center h5">Sin Pedidos pendientes</p>
        </div>

    </div>
  </AuthenticatedLayout>
</template>
