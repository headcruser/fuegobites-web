<script setup>
import {
    MDBRow,
    MDBCol,
    MDBCard,
    MDBCardBody,
    MDBSelect,
    MDBModal,
    MDBModalHeader,
    MDBModalTitle,
    MDBModalBody,
    MDBModalFooter,
    MDBBtn,
} from "mdb-vue-ui-kit";

import {ref,onMounted,onBeforeMount,computed} from "vue";

import _ from "lodash";

import moment from "moment";
import 'moment/dist/locale/es-mx'

import { router} from '@inertiajs/vue3'

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Calendar from 'primevue/calendar';

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from '@/Components/Pagination.vue';


const props = defineProps({
    total_pagado: Number,
    total_efectivo: Number,
    total_transferencia: Number,
    fecha:String,
    fecha_antes: String,
    fecha_despues: String,
    ventas: Object,
    tipo:String,
    paginacion:Number
});


const totalPorPagina = computed(() => Intl.NumberFormat().format( _.sumBy(props.ventas.data,(item) => Number(item.total)) ));

const tituloFecha = ref('');

const pages = ref([
    { text: "10", value: 10 },
    { text: "25", value: 25 },
    { text: "50", value: 50 },
    { text: "100", value: 100 },
    { text: "250", value: 250 },
])

const pageSelected = ref(props.paginacion);

const modalCalendar = ref(false);

const dateCalendar = ref()

const invalidCalendar = ref(false);

onBeforeMount(() => {
})

onMounted(() => {
    tituloFecha.value = makeTitleDate()
})

const makeTitleDate = () => {
    const info = {
        dia:  moment(props.fecha).format('ll'),
        semanal: `Del ${moment(props.fecha_antes).format('DD MMM')} al ${moment(props.fecha_despues).format('DD MMM')} `,
        mensual:_.upperFirst(moment(props.fecha).format('MMMM')),
        anual: moment(props.fecha).format('YYYY'),
    }

    return info[props.tipo] ?? '';
}

const evtShowCalendar = () => {
    modalCalendar.value = true;
}

const evtChangeDate = () => {
    invalidCalendar.value = false;

    if(!dateCalendar.value ){
        invalidCalendar.value = true;
    }

    modalCalendar.value = false

    router.get(route('ventas.reporte.index'),{
            tipo: props.tipo,
            paginacion: Number(pageSelected.value),
            fecha: moment(dateCalendar.value).format('YYYY-MM-DD')
        },
        {
            preserveState:false,
            replace:true,
        }
    );
}

const evtChangePaginate = () => {
    router.get(route('ventas.reporte.index'),{
            tipo: props.tipo,
            paginacion: Number(pageSelected.value),
            fecha: moment(props.fecha).format('YYYY-MM-DD')
        },
        {
            preserveState:true,
            replace:true,
        }
    );
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-3 mx-3">
        <h4 class="mb-0">Reporte de Ventas</h4>

        <MDBRow>
            <MDBCol>
                <div class="btn-group mt-3 mb-3">
                    <a
                      :href="route('ventas.reporte.index',{ tipo: 'dia' })"
                      class="btn"
                      :class="{'btn-primary':props.tipo == 'dia','btn-white':props.tipo != 'dia'}">Día</a>

                    <a
                      :href="route('ventas.reporte.index',{ tipo: 'semanal' })"
                      class="btn"
                      :class="{'btn-primary':props.tipo == 'semanal','btn-white':props.tipo != 'semanal'}"
                      >Semanal</a>
                    <a
                      :href="route('ventas.reporte.index',{ tipo: 'mensual'})"
                      class="btn"
                      :class="{'btn-primary':props.tipo == 'mensual','btn-white':props.tipo != 'mensual'}"
                      >Mensual</a>

                    <a
                      :href="route('ventas.reporte.index',{ tipo: 'anual' })"
                      :class="{'btn-primary':props.tipo == 'anual','btn-white':props.tipo != 'anual'}"
                      class="btn">Anual</a>
                </div>
            </MDBCol>
        </MDBRow>

        <MDBRow>
            <MDBCol sm="12" lg="9" class="mb-3">
                <MDBCard>
                    <MDBCardBody>
                        <MDBRow class="mb-2 d-flex align-content-center ">
                            <MDBCol>
                                <a :href="route('ventas.reporte.index',{
                                        tipo: props.tipo,
                                        fecha: moment(props.fecha_antes).format('YYYY-MM-DD'),
                                        paginacion: Number(pageSelected)
                                    })"
                                    data-toggle="tooltip"
                                    data-placement="top" class="btn btn-primary btn-sm text-white no_print">
                                    <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                                </a>
                            </MDBCol>

                            <MDBCol class="text-center">
                                <h3 class="text-nowrap"> {{ tituloFecha }}
                                    <small>
                                        <i
                                          class="far fa-calendar-alt text-primary"
                                          title="Selecciona una fecha"
                                          @click="evtShowCalendar"
                                        />
                                    </small>
                                </h3>
                            </MDBCol>

                            <MDBCol class="text-end">
                                <a :href="route('ventas.reporte.index',{
                                    tipo: props.tipo,
                                    fecha: moment(props.fecha_despues).format('YYYY-MM-DD'),
                                    paginacion: Number(pageSelected)
                                })"
                                    data-toggle="tooltip"
                                    data-placement="top" class="btn btn-primary btn-sm text-white no_print">
                                    <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                                </a>
                            </MDBCol>
                        </MDBRow>

                        <MDBRow>
                            <MDBCol>
                                <MDBSelect
                                    class="w-25"
                                    size="sm"
                                    v-model:options="pages"
                                    v-model:selected="pageSelected"
                                    @change="evtChangePaginate"
                                />
                            </MDBCol>
                            <MDBCol class="text-center">
                                <b>Total:</b> {{ props.ventas.total }}
                            </MDBCol>
                            <MDBCol>
                                <Pagination class="mt-6" :links="props.ventas.links" />
                            </MDBCol>
                        </MDBRow>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered w-100" id="tabla_areas" >
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Fecha pago</th>
                                        <th>Forma Pago</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in props.ventas.data">
                                        <td>Venta</td>
                                        <td>
                                            <div>
                                                <small class="text-muted">
                                                    {{ moment(venta.fecha_pago).format('LL') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>{{ venta.forma_pago }}</td>
                                        <td class="text-end d-flex justify-content-between"> <span>$</span>  <span>{{ venta.total }}</span> </td>
                                    </tr>

                                </tbody>
                                <tfoot class="bg-secondary">
                                    <tr>
                                        <th colspan="2"></th>
                                        <th >Total</th>
                                        <th class="text-end text-nowrap d-flex justify-content-between">
                                            <span>$</span> <span>{{ totalPorPagina }}</span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </MDBCardBody>
                </MDBCard>
            </MDBCol>

            <MDBCol sm="12" lg="3">
                <div class="card shadow-0 bg-primary text-white mb-3">
                    <div class="card-body">
                        <p class="text-uppercase small mb-2">
                            <strong>INGRESO TOTAL</strong>
                        </p>
                        <h6 class="mb-0">
                            <strong>{{ props.total_pagado }}</strong>
                        </h6>
                    </div>
                </div>

                <div class="card shadow-0 bg-primary text-white mb-3">
                    <div class="card-body">
                        <p class="text-uppercase small mb-2">
                            <strong>EFECTIVO</strong>
                        </p>
                        <h5 class="mb-0">
                            <strong>{{ props.total_efectivo }}</strong>
                        </h5>
                    </div>
                </div>

                <div class="card shadow-0 bg-primary text-white mb-3">
                    <div class="card-body">
                        <p class="text-uppercase small mb-2">
                            <strong>TRANSFERENCIA</strong>
                        </p>
                        <h5 class="mb-0">
                            <strong>{{ props.total_transferencia }}</strong>
                        </h5>
                    </div>
                </div>
            </MDBCol>
        </MDBRow>

    </div>


    <MDBModal
        id="modalCalendar"
        tabindex="-1"
        labelledby="modalCalendarLabel"
        v-model="modalCalendar"
    >
        <MDBModalHeader>
            <MDBModalTitle id="modalCalendarLabel">Selecciona la fecha</MDBModalTitle>
        </MDBModalHeader>
        <MDBModalBody>
            <span :class="{'text-danger':invalidCalendar}">(*) Debes seleccionar una fecha</span>
            <Calendar
              class="w-100"
              v-model="dateCalendar "
              inline
              showWeek />
        </MDBModalBody>
        <MDBModalFooter>
            <MDBBtn color="secondary" @click="modalCalendar = false">Cerrar</MDBBtn>
            <MDBBtn color="primary" @click="evtChangeDate">Guardar</MDBBtn>
        </MDBModalFooter>
    </MDBModal>
  </AuthenticatedLayout>
</template>
