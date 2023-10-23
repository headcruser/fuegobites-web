<script setup>
import {
    MDBRow,
    MDBCol,
    MDBCard,
    MDBCardBody,
} from "mdb-vue-ui-kit";

import {onMounted,ref} from "vue";


import _ from "lodash";

import moment from "moment";
import 'moment/dist/locale/es-mx'

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from '@/Components/Pagination.vue';


import { onBeforeMount,computed } from "vue";

const props = defineProps({
    total_pagado: Number,
    total_efectivo: Number,
    total_transferencia: Number,
    fecha:String,
    fecha_antes: String,
    fecha_despues: String,
    ventas: Object,
    tipo:String
});


const totalPorPagina = computed(() => Intl.NumberFormat().format( _.sumBy(props.ventas.data,(item) => Number(item.total)) ));

onBeforeMount(() => {
})

onMounted(() => {
})


</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-3 mx-3">
        <h4 class="mb-0">Reporte de Ventas</h4>

        <MDBRow>
            <MDBCol sm="12" lg="9">
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

                <MDBCard>
                    <MDBCardBody>
                        <MDBRow class="mb-2">
                            <MDBCol>
                                <a :href="route('ventas.reporte.index',{ tipo: 'dia',fecha: moment(props.fecha_antes).format('YYYY-MM-DD') })"
                                    data-toggle="tooltip"
                                    data-placement="top" class="btn btn-primary btn-sm text-white no_print">
                                    <i class="fas fa-arrow-alt-circle-left fa-2x"></i>
                                </a>
                            </MDBCol>

                            <MDBCol class="text-center">
                                <h3> {{ moment(props.fecha).format('LL') }}
                                    <small><a class="no_print" data-toggle="modal" data-target="#seleccionarFecha">
                                        <i class="far fa-calendar-alt"></i></a>
                                    </small>
                                </h3>
                            </MDBCol>

                            <MDBCol class="text-end">
                                <a :href="route('ventas.reporte.index',{ tipo: 'dia',fecha: moment(props.fecha_despues).format('YYYY-MM-DD') })"
                                    data-toggle="tooltip"
                                    data-placement="top" class="btn btn-primary btn-sm text-white no_print">
                                    <i class="fas fa-arrow-alt-circle-right fa-2x"></i>
                                </a>
                            </MDBCol>
                        </MDBRow>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover w-100" id="tabla_areas" >
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Fecha</th>
                                        <th>Forma Pago</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in props.ventas.data">
                                        <td>Venta</td>
                                        <td>
                                            <div>
                                                {{ moment(venta.fecha).format('LL') }}
                                            </div>
                                            <div>
                                                <small class="text-muted">
                                                   <b class="text-dark">Fecha Pago:</b>  {{ moment(venta.fecha_pago).format('LL') }}
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

                        <MDBRow>
                            <MDBCol>
                                <b>Total:</b> {{ props.ventas.total }}
                            </MDBCol>
                            <MDBCol>
                                <Pagination class="mt-6" :links="props.ventas.links" />
                            </MDBCol>
                        </MDBRow>
                    </MDBCardBody>
                </MDBCard>
            </MDBCol>

            <MDBCol sm="12" lg="3">
                <div class="card shadow-0 bg-primary text-white mb-3">
                    <div class="card-body">
                        <p class="text-uppercase small mb-2">
                            <strong>INGRESO TOTAL</strong>
                        </p>
                        <h5 class="mb-0">
                            <strong>{{ props.total_pagado }}</strong>
                        </h5>
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
  </AuthenticatedLayout>
</template>
