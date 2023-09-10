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
} from "mdb-vue-ui-kit";

import { computed, onMounted, ref } from "vue";
import { useForm} from "@inertiajs/vue3";

import _ from "lodash";
import moment from "moment";

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Editor from 'primevue/editor';
import Sidebar from 'primevue/sidebar';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    ventas: Object
});

const totalPedidos = computed(() =>  Intl.NumberFormat().format(_.sumBy(props.ventas,(venta) => Number(venta.total)) ))

onMounted(() => {
    moment.locale('es-mx');
})

const modalAgregarPedido = ref(false);

const ventaSeleccionada = ref();

const form = useForm({
    nombre:'',
    descripcion:'',
    total:'',
    pagado:false
})

const visibleRight = ref(false);


const evtClickVenta = (venta) => {
    ventaSeleccionada.value = venta;

    visibleRight.value = true;
}


const evtClickRegistrar = () => {
    modalAgregarPedido.value = false;

    form.transform((data) => ({
        ...data,
    }))

    form.post(route('ventas.registro.store'),{
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    })
}

</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-4 mx-4">
        <h4 class="mb-0">Ventas</h4>

        <section class="pt-3">
            <article class="mb-3">
                <div class="bg-gradient bg-light border d-flex justify-content-between p-2 align-content-center" style="height: 60px;">
                    <div>
                        <MDBBtn @click="modalAgregarPedido=true">Agregar pedido</MDBBtn>
                    </div>
                    <div class="fw-lighter">Total: $ {{ totalPedidos }}</div>
                </div>
            </article>

            <MDBListGroup light>
                <MDBListGroupItem
                    class="d-flex justify-content-between align-items-center"
                    v-for="venta  in props.ventas"
                    :key="venta.id"
                    @click="evtClickVenta(venta)"
                    >
                    <div class="d-flex align-items-center">
                        <div>
                            <i class="fa-lg" :class="{'far fa-circle':!venta.pagado ,'fa fa-circle-check' : venta.pagado}"></i>
                        </div>
                        <div class="ms-3">
                            <p class="fw-bold mb-1">{{ venta.nombre }}</p>
                            <p class="text-muted mb-0">Mañana {{ moment(venta.fecha).format('ll') }}</p>
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
            <MDBModalTitle id="modalAgregarPedidoLabel"> Registro de peedidos </MDBModalTitle>
        </MDBModalHeader>
        <MDBModalBody>

            <div class="form-group">
                <MDBInput
                    type="text"
                    v-model="form.nombre"
                    label="Nombre"
                    wrapperClass="mb-4"
                />
                <div v-if="form.errors.nombre" class="invalid-feedback">
                    {{ form.errors.nombre }}
                </div>
            </div>

            <div class="form-group">
                <MDBInput
                    type="number"
                    v-model="form.total"
                    class="active"
                    label="Precio"
                    title="Precio"
                    wrapperClass="mb-4"
                />
                <div v-if="form.errors.nombre" class="invalid-feedback">
                    {{ form.errors.nombre }}
                </div>
            </div>

            <div class="form-group">
                <Editor v-model="form.descripcion" editorStyle="height: 320px" />
            </div>

            <div class="form-group mt-3">
                <MDBSwitch
                    label="Pagado"
                    v-model="form.pagado"
                />
            </div>


        </MDBModalBody>
        <MDBModalFooter>
            <MDBBtn color="secondary" @click="modalAgregarPedido = false">Cerrar</MDBBtn>
            <MDBBtn color="primary" @click="evtClickRegistrar">Registrar</MDBBtn>
        </MDBModalFooter>
    </MDBModal>



    <Sidebar v-model:visible="visibleRight" position="right" class="bg-white" style="width: 50rem;" >

        <h2>Detalle del pedido</h2>
        <p class="text-muted">{{ ventaSeleccionada.nombre }}</p>
        <p>  {{ moment(ventaSeleccionada.fecha).format('LL') }} </p>

        <p>{{ ventaSeleccionada.descripcion }}</p>
    </Sidebar>
  </AuthenticatedLayout>
</template>
