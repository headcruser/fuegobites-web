<script setup>
import {
MDBCol,
MDBRow,
MDBInput,
MDBContainer,
MDBIcon,
MDBBtn,
MDBBreadcrumb,
MDBBreadcrumbItem,
MDBModal,
MDBModalHeader,
MDBModalTitle,
MDBModalBody,
MDBModalFooter,
MDBDropdown,
MDBDropdownToggle,
MDBDropdownMenu,
MDBDropdownItem,
MDBLoading,
MDBTextarea,
MDBDatepicker,
} from "mdb-vue-ui-kit";

import {onBeforeMount, ref } from "vue";
import _ from "lodash";
import Swal from 'sweetalert2'

import 'moment/dist/locale/es-mx'
import moment from "moment";

import { useForm ,router} from "@inertiajs/vue3";

import logotipo from '@/img/fuego-bites.png';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import VueMultiselect from 'vue-multiselect'
import { useClientes } from "@/Composables/useClientes";
import { reactive } from "vue";

const clienteQuery =  useClientes();

const props = defineProps({
    cotizacion: Object,
    company_name:String,
    company_web:String,
});

const modalCambiarCliente = ref(false);
const modalAgregarConcepto = ref(false);
const modalAjustes = ref(false);
const modalEnviarCotizacion = ref(false);
const modalEditarConcepto = ref(false);

const dropdownOptions = ref(false)

const selectIdCliente = ref();
const optionClientes = ref([]);

const isLoadingSelectCliente = ref(false);

const isLoading = ref(false);

const formAddPartida = useForm({
    concepto: '',
    descripcion:'',
    cantidad:'',
    precio: '',
    total: '',
    moneda:'MXN'
});

const formEditarPartida = useForm({
    id_partida: 0,
    concepto: '',
    descripcion:'',
    cantidad:'',
    precio: '',
    total: '',
    moneda:'MXN'
});

const formAjusteCotizacion = useForm({
    saludo:props.cotizacion.saludo,
    descripcion:props.cotizacion.descripcion,
    observaciones:props.cotizacion.observaciones,
    pie:props.cotizacion.pie,
    nombre: props.cotizacion.nombre,
    fecha: moment(props.cotizacion).format('DD/MM/YYYY')
});

const formChangeClient = useForm({
    id_cliente:''
})

const formEnviarCotizacion = useForm({
    email: props.cotizacion?.cliente?.email ?? '',
    email_cc: [],
})

const listEmailCC = reactive([]);

onBeforeMount(() => {
    moment.locale('es-mx');
})

const asyncFind = async (query) => {
    isLoadingSelectCliente.value = true;

    const clientes = await clienteQuery.searchByName(query);

    optionClientes.value = clientes.map(cliente => {
        return {
            name: cliente.nombre,
            value: cliente.id
        };
    })

    isLoadingSelectCliente.value = false;
}

const evtShownModalCliente = async () => {
    isLoadingSelectCliente.value = true;

    const clientes = await clienteQuery.searchByName("");

    optionClientes.value = clientes.map(cliente => {
        return {
            name: cliente.nombre,
            value: cliente.id
        };
    })

    isLoadingSelectCliente.value = false;
}

const evtAgregarPartida = ($evt) => {
    $evt.preventDefault();

    modalAgregarConcepto.value = true;
}

const evtShowEditPartida = async (partida) => {
    formEditarPartida.id_partida = partida.id,
    formEditarPartida.concepto = partida.concepto;
    formEditarPartida.descripcion = partida.descripcion;
    formEditarPartida.cantidad = partida.cantidad;
    formEditarPartida.precio = partida.precio;
    formEditarPartida.total = partida.total;

    modalEditarConcepto.value = true;
}

const evtClickSaveEditConcept = async () => {

    isLoading.value = true

    formEditarPartida.transform((data) => ({
        ...data,
        _method: 'put',
    }));

    formEditarPartida.post(route('ventas.cotizaciones.actualizar-partida',formEditarPartida.id_partida), {
        onError: (response) => {
            isLoading.value = false;
            Swal.fire("Error", "No se registro el concepto correctamente", "error");
        },
        onSuccess:() => {
            isLoading.value = false;
            modalAgregarConcepto.value = false;
        }
    });
}

const evtEliminarPartida = async (partida) => {
    const result = await Swal.fire({
        title: '¿Deseas eliminar la partida?',
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

    router.delete(route('ventas.cotizaciones.eliminar-partida', partida.id), {
        onSuccess: () => {
            Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success");
        },
    });
}

const evtCambiarDestinatario = () => {
    modalCambiarCliente.value = true
}

const evtClickRegistrar = () => {
    formChangeClient.id_cliente = selectIdCliente.value.value;
    isLoading.value = true;

    formChangeClient.post(route('ventas.cotizaciones.cambiar-cliente',props.cotizacion),{
        onError: (errors) => {
            isLoading.value = false

            const msgErrors = Object.entries(errors).map(([key,value]) => `${key}: ${value}`).join('');

            Swal.fire("Error", msgErrors, "error");
        },
        onSuccess:() => {
            isLoading.value = false
        }
    });

    modalCambiarCliente.value = false;
}

const evtClickSaveConcept = () => {
    isLoading.value = true

    formAddPartida.transform((data) => ({
        ...data,
    }));

    formAddPartida.post(route('ventas.cotizaciones.agregar-partida',props.cotizacion), {
        onError: (errors) => {
            isLoading.value = false

            const msgErrors = Object.entries(errors).map(([key,value]) => `${key}: ${value}`).join('');

            Swal.fire("Error", msgErrors, "error");
        },
        onSuccess:() => {
            isLoading.value = false
            modalAgregarConcepto.value = false;
        }
    });
}

const evtShowAjustes = () => {
    modalAjustes.value = true;
}

const evtClickGuardarAjuestes = () => {

    isLoading.value = true

    formAjusteCotizacion.transform((data) => ({
        ...data,
        _method: 'put',
    }));

    formAjusteCotizacion.post(route('ventas.cotizaciones.update',props.cotizacion), {

        onError: (errors) => {
            const msgErrors = Object.entries(errors).map(([key,value]) => `${key}: ${value}`).join('');
            isLoading.value = false;

            Swal.fire("Error", msgErrors, "error");
        },
        onSuccess:() => {
            isLoading.value = false;
            modalAjustes.value = false;
        }
    });

}

const evtShowCotizacion = () => {
    modalEnviarCotizacion.value = true;
}

const onClicAddCopy = () => {
    listEmailCC.push({
        input: ''
    });
}

const onClickRemoveEmail = (index) => {
    listEmailCC.splice(index,1);
}

const evtClickEnviarCotizacion = async () => {
    isLoading.value = true

    formEnviarCotizacion.transform((data) => ({
        ...data,
        email_cc: listEmailCC.map(element => element.input),
    }));

    formEnviarCotizacion.post(route('ventas.cotizaciones.enviar-cotizacion',props.cotizacion), {

        onError: (errors) => {
            const msgErrors = Object.entries(errors).map(([key,value]) => `${key}: ${value}`).join('');
            isLoading.value = false;
            Swal.fire("Error", msgErrors, "error");
        },
        onSuccess:() => {
            isLoading.value = false;
            modalAgregarConcepto.value = false;
        }
    });
}
</script>
<template>
    <MDBLoading v-model="isLoading" />
    <AuthenticatedLayout>
        <div class="mt-4 mx-4">
            <MDBBreadcrumb>
                <MDBBreadcrumbItem><a :href="route('dashboard')">Inicio</a></MDBBreadcrumbItem>
                <MDBBreadcrumbItem ><a :href="route('ventas.cotizaciones.index')">Cotizaciones</a></MDBBreadcrumbItem>
                <MDBBreadcrumbItem ><a :href="route('ventas.cotizaciones.edit',cotizacion.id)">Editar cotizacion</a></MDBBreadcrumbItem>
            </MDBBreadcrumb>

            <MDBContainer fluid class="mb-4">
                <div class="align-self-center container-xs-factura p-4"
                    style="background-color: white; box-shadow: 1px 1px 2px 0px #757575;width: 100%;max-width: 100%;" >

                    <div class="row align-items-center pb-4">
                        <div class="col">
                            <img :src="logotipo"
                                style="width: 5rem;height: 5rem;"
                                alt="Fuego bites"
                                draggable="false" />


                            <span class="h2 ms-3">{{ company_name }}</span>
                        </div>

                        <div class="col text-end">
                            <div class="fs-5">
                                <span> <b>FECHA:</b> {{ moment(cotizacion.fecha).format('LL') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row pb-4">
                        <div class="col-sm-6 text-center text-sm-start">
                            <h5 class="mb-3 mb-sm-0 mt-1">Cotización # {{ cotizacion.folio }}</h5>
                        </div>
                        <div class="col-sm-6 text-center text-sm-end">
                            <MDBDropdown btnGroup v-model="dropdownOptions">
                                <MDBDropdownToggle @click="dropdownOptions = !dropdownOptions" color="link">
                                    <i class="fas fa-cog me-2"></i>OPCIONES
                                </MDBDropdownToggle>
                                <MDBDropdownMenu>
                                    <MDBDropdownItem target="_blank" :href="route('ventas.cotizaciones.show',cotizacion.id)">VER PDF</MDBDropdownItem>
                                    <MDBDropdownItem href="#" @click="evtShowAjustes" >AJUSTES</MDBDropdownItem>
                                    <MDBDropdownItem href="#" @click="evtShowCotizacion">ENVIAR COTIZACION</MDBDropdownItem>
                                    <MDBDropdownItem href="#" @click="evtAgregarPartida">AGREGAR CONCEPTO</MDBDropdownItem>
                                </MDBDropdownMenu>
                            </MDBDropdown>
                        </div>
                    </div>

                    <p>
                        <span>{{ cotizacion.saludo }}</span> <span class="text-capitalize fw-bolder">{{ cotizacion?.cliente?.nombre }}</span>

                        <i
                          class="fas fa-edit ms-2"
                          style="cursor:pointer"
                          id="cambiar_destinatario"
                          @click="evtCambiarDestinatario"
                          ></i>
                    </p>

                    <p>
                        {{ cotizacion.descripcion }}
                    </p>

                    <div class="table-responsive pt-2">
                        <table class="table w-100" style="border-collapse: separate;" >
                            <thead>
                                <tr>
                                    <th class="text-center bg-primary text-white" style="width:120px">Acciones</th>
                                    <th class="text-center bg-primary text-white">Partida</th>
                                    <th class="text-center bg-primary text-white">Concepto</th>
                                    <th class="text-center bg-primary text-white">Cantidad</th>
                                    <th class="text-center bg-primary text-white text-nowrap ">Precio Unitario</th>
                                    <th class="text-center bg-primary text-white">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="partida in cotizacion.partidas" :key="partida.id">
                                    <td class="text-nowrap">
                                        <MDBBtn outline="primary"
                                            title="editar"
                                            size="small"
                                            floating
                                            @click="evtShowEditPartida(partida)"
                                        >
                                            <MDBIcon  icon="pencil"></MDBIcon>
                                        </MDBBtn>

                                        <MDBBtn outline="danger"
                                            title="Eliminar"
                                            size="small"
                                            floating
                                            @click="evtEliminarPartida(partida)"
                                        >
                                            <MDBIcon  icon="trash"></MDBIcon>
                                        </MDBBtn>
                                    </td>
                                    <td class="text-center">{{ partida.posicion }}</td>
                                    <td>
                                        <p class="fw-normal mb-1">{{ partida.concepto }}</p>
                                        <p class="text-muted mb-0">{{ partida.descripcion }}</p>
                                    </td>
                                    <td class="text-center">{{ partida.cantidad }}</td>
                                    <td class="text-end">{{ partida.precio }}</td>
                                    <td class="text-end">{{ partida.total }}</td>
                                </tr>
                            </tbody>
                            <tfoot>

                                <tr style="background:#ccc">
                                    <td colspan=5 class="text-end fw-bold">Total: </td>
                                    <td colspan=2 class="text-end bg-white">
                                        <b>$ {{ _.sumBy(cotizacion.partidas, (partida) => Number(partida.total)) }}</b>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <table class="table w-100 border">
                            <thead>
                                <tr class="border">
                                    <th class="text-center bg-primary text-white">
                                        <b>OBSERVACIONES</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr     >
                                    <td >
                                        <p>
                                            {{cotizacion.observaciones}}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                         <p>
                            {{ cotizacion.pie }}
                        </p>

                        <p class="text-end">
                            <b>Atentamente: </b><br>
                            <span class="text-primary"><b>{{ cotizacion.vendedor.name }}</b></span><br>
                            <span class="text-primary">C:</span>{{ cotizacion.vendedor.email }}<br>
                            <span class="text-primary">W:</span>{{ company_web }}<br>
                        </p>
                    </div>

                </div>
            </MDBContainer>
        </div>


        <!-- ENVIAR COTIZACION -->
        <MDBModal
            id="modalCambiarCliente"
            tabindex="-1"
            labelledby="modalCambiarClienteLabel"
            v-model="modalEnviarCotizacion"
            staticBackdrop
        >
            <MDBModalHeader>
                <MDBModalTitle id="modalCambiarClienteLabel">Enviar cotizacion</MDBModalTitle>
            </MDBModalHeader>
            <MDBModalBody>


                <MDBRow>
                    <MDBCol>
                        <MDBInput
                            v-model="formEnviarCotizacion.email"
                            label="Correo"
                            title="Correo"
                            type="email"
                            required
                        />
                    </MDBCol>
                </MDBRow>

                <MDBRow class="pt-4">
                    <MDBCol>

                        <MDBBtn outline="secondary" size="sm"  @click="onClicAddCopy">Agregar CC</MDBBtn>
                        <hr class="mb-4 mt-1 p-0">

                        <template v-for="(inputValue,index) in listEmailCC" :key="index">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <MDBInput
                                        v-model="inputValue.input"
                                        label="Correo"
                                        title="Correo"
                                        type="email"
                                        required
                                    />
                                </div>
                                <div>
                                    <MDBBtn color="danger" @click="onClickRemoveEmail(index)">
                                        <MDBIcon icon="trash"></MDBIcon>
                                    </MDBBtn>
                                </div>
                            </div>
                        </template>
                    </MDBCol>
                </MDBRow>
            </MDBModalBody>
            <MDBModalFooter>
                <MDBBtn color="secondary" @click="modalEnviarCotizacion = false">Cerrar</MDBBtn>
                <MDBBtn color="primary" @click="evtClickEnviarCotizacion">Enviar</MDBBtn>
            </MDBModalFooter>
        </MDBModal>

        <!-- SELECCIONAR CLIENTE -->
        <MDBModal
            id="modalCambiarCliente"
            tabindex="-1"
            labelledby="modalCambiarClienteLabel"
            v-model="modalCambiarCliente"
            @shown="evtShownModalCliente"
            staticBackdrop
        >
            <MDBModalHeader>
                <MDBModalTitle id="modalCambiarClienteLabel">Cambiar cliente</MDBModalTitle>
            </MDBModalHeader>
            <MDBModalBody>
                <VueMultiselect
                    v-model="selectIdCliente"
                    :options="optionClientes"
                    :searchable="true"
                    :show-labels="false"
                    :allow-empty="false"
                    @search-change="asyncFind"
                    :loading="isLoadingSelectCliente"
                    label="name"
                    placeholder="Selecciona un cliente"
                    track-by="name"
                    :preserve-search="true"
                >
                    <template #noResult>
                        No se encontraron resultados
                    </template>
                </VueMultiselect>

            </MDBModalBody>
            <MDBModalFooter>
                <MDBBtn color="secondary" @click="modalCambiarCliente = false">Cerrar</MDBBtn>
                <MDBBtn color="primary" @click="evtClickRegistrar">Guardar</MDBBtn>
            </MDBModalFooter>
        </MDBModal>

        <!-- AGREGAR CONCEPTO -->
        <MDBModal
            id="modalAgregarconcepto"
            tabindex="-1"
            labelledby="modalAgregarConceptoClienteLabel"
            v-model="modalAgregarConcepto"
            staticBackdrop
        >
            <MDBModalHeader>
                <MDBModalTitle id="modalCambiarClienteLabel">Agregar concepto</MDBModalTitle>
            </MDBModalHeader>
            <MDBModalBody>

                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAddPartida.concepto"
                          label="Concepto"
                          title="Concepto"
                          required
                        />

                        <div v-if="formAddPartida.errors.concepto" class="invalid-feedback">
                            {{ formAddPartida.errors.concepto }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow  class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAddPartida.descripcion"
                          label="Descripción"
                          title="Descripción"
                          required
                        />

                        <div v-if="formAddPartida.errors.descripcion" class="invalid-feedback">
                            {{ formAddPartida.errors.descripcion }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow class="mb-4">
                    <MDBCol class="col-6">
                        <MDBInput
                          v-model.number="formAddPartida.cantidad"
                          label="Cantidad"
                          title="Cantidad"
                          required
                        />

                        <div v-if="formAddPartida.errors.cantidad" class="invalid-feedback">
                            {{ formAddPartida.errors.cantidad }}
                        </div>
                    </MDBCol>

                    <MDBCol class="col-6">
                        <MDBInput
                          v-model.number="formAddPartida.precio"
                          label="Precio"
                          title="Precio"
                          required
                        />

                        <div v-if="formAddPartida.errors.precio" class="invalid-feedback">
                            {{ formAddPartida.errors.precio}}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow>
                    <MDBRow>
                        <span class="fs-5"> <b>Total <i class="fas fa-dollar-sign"></i></b> {{ formAddPartida.cantidad *  formAddPartida.precio}}</span>
                    </MDBRow>
                </MDBRow>

            </MDBModalBody>
            <MDBModalFooter>
                <MDBBtn color="secondary" @click="modalAgregarConcepto = false">Cerrar</MDBBtn>
                <MDBBtn color="primary" @click="evtClickSaveConcept">Guardar</MDBBtn>
            </MDBModalFooter>
        </MDBModal>


        <!-- EDITAR CONCEPTO -->
        <MDBModal
            id="modalEditarConcepto"
            tabindex="-1"
            labelledby="modalEditarConceptoLabel"
            v-model="modalEditarConcepto"
            staticBackdrop
        >
            <MDBModalHeader>
                <MDBModalTitle id="modalEditarConceptoLabe">Editar concepto</MDBModalTitle>
            </MDBModalHeader>
            <MDBModalBody>

                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formEditarPartida.concepto"
                          label="Concepto"
                          title="Concepto"
                          required
                        />

                        <div v-if="formEditarPartida.errors.concepto" class="invalid-feedback">
                            {{ formEditarPartida.errors.concepto }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow  class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formEditarPartida.descripcion"
                          label="Descripción"
                          title="Descripción"
                          required
                        />

                        <div v-if="formEditarPartida.errors.descripcion" class="invalid-feedback">
                            {{ formEditarPartida.errors.descripcion }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow class="mb-4">
                    <MDBCol class="col-6">
                        <MDBInput
                          v-model.number="formEditarPartida.cantidad"
                          label="Cantidad"
                          title="Cantidad"
                          required
                        />

                        <div v-if="formEditarPartida.errors.cantidad" class="invalid-feedback">
                            {{ formEditarPartida.errors.cantidad }}
                        </div>
                    </MDBCol>

                    <MDBCol class="col-6">
                        <MDBInput
                          v-model.number="formEditarPartida.precio"
                          label="Precio"
                          title="Precio"
                          required
                        />

                        <div v-if="formEditarPartida.errors.precio" class="invalid-feedback">
                            {{ formEditarPartida.errors.precio}}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow>
                    <MDBRow>
                        <span class="fs-5"> <b>Total <i class="fas fa-dollar-sign"></i></b> {{ formEditarPartida.cantidad *  formEditarPartida.precio}}</span>
                    </MDBRow>
                </MDBRow>

            </MDBModalBody>
            <MDBModalFooter>
                <MDBBtn color="secondary" @click="modalEditarConcepto = false">Cerrar</MDBBtn>
                <MDBBtn color="primary" @click="evtClickSaveEditConcept">Guardar</MDBBtn>
            </MDBModalFooter>
        </MDBModal>

        <!-- MODAL AJUSTES -->
        <MDBModal
            id="modalAjustes"
            tabindex="-1"
            labelledby="modalAjustesLabel"
            v-model="modalAjustes"
            staticBackdrop
        >
            <MDBModalHeader>
                <MDBModalTitle id="modalAjustesLabel">Ajustes de la cotización</MDBModalTitle>
            </MDBModalHeader>
            <MDBModalBody>

                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAjusteCotizacion.nombre"
                          label="Nombre"
                          title="Nombre"
                          required
                        />

                        <div v-if="formAjusteCotizacion.errors.nombre" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.nombre }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBDatepicker
                            v-model="formAjusteCotizacion.fecha"
                            :class="{'is-invalid':formAjusteCotizacion.errors.fecha}"
                            :invalid-feedback="'Selecciona una fecha '"
                            label="Fecha entrega"
                            title="Fecha entrega"
                            format="DD/MM/YYYY"
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

                        <div v-if="formAjusteCotizacion.errors.fecha" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.fecha }}
                        </div>
                    </MDBCol>
                </MDBRow>



                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAjusteCotizacion.saludo"
                          label="Saludo"
                          title="Saludo"
                          required
                        />

                        <div v-if="formAjusteCotizacion.errors.saludo" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.saludo }}
                        </div>
                    </MDBCol>
                </MDBRow>

                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAjusteCotizacion.descripcion"
                          label="Descripción"
                          title="Descripción"
                          required
                        />

                        <div v-if="formAjusteCotizacion.errors.descripcion" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.descripcion }}
                        </div>
                    </MDBCol>
                </MDBRow>
                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBInput
                          v-model="formAjusteCotizacion.observaciones"
                          label="Observaciones"
                          title="Observaciones"
                          required
                        />

                        <div v-if="formAjusteCotizacion.errors.observaciones" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.observaciones }}
                        </div>
                    </MDBCol>
                </MDBRow>
                <MDBRow class="mb-4">
                    <MDBCol class="col-12">
                        <MDBTextarea
                          v-model="formAjusteCotizacion.pie"
                          label="Pie"
                          title="Pie"
                          rows="4"
                          required
                        />

                        <div v-if="formAjusteCotizacion.errors.pie" class="invalid-feedback">
                            {{ formAjusteCotizacion.errors.pie }}
                        </div>
                    </MDBCol>
                </MDBRow>

            </MDBModalBody>
            <MDBModalFooter>
                <MDBBtn color="secondary" @click="modalAjustes = false">Cerrar</MDBBtn>
                <MDBBtn color="primary" @click="evtClickGuardarAjuestes">Guardar</MDBBtn>
            </MDBModalFooter>
        </MDBModal>
    </AuthenticatedLayout>
</template>


