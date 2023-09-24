<script setup>
import {
    MDBBtn,
    MDBInput,
    MDBListGroup,
    MDBListGroupItem,
    MDBSwitch,
    MDBRow,
    MDBCol,
    MDBDatepicker,
    MDBSelect,
} from "mdb-vue-ui-kit";

import { ref,computed ,onMounted } from "vue";

import _ from "lodash";

import defaultProducto  from '@/img/default-product.png';

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";
import Editor from 'primevue/editor';

const props = defineProps({
    nombre: String,
    nombre_errors: Array,

    producto: String,
    productos_options: Array,
    productos_errors: String,

    descripcion: String,
    descripcion_errors: String,

    fecha: String,
    fecha_errors: Array,

    forma_pago: String,
    forma_pago_errors: String,
    formas_pago: Array,


    pagado: Boolean,

    detalles_ventas: Array
});

onMounted(() => {
    detalles_ventas.value = [...props.detalles_ventas];
})


const emit = defineEmits([
    'update:nombre',
    'update:nombre_errors',

    'update:descripcion',
    'update:descripcion_errors',

    'update:fecha',
    'update:fecha_errors',

    'update:forma_pago',
    'update:formas_pago',

    'update:producto',
    'update:productos_options',
    'update:productos_errors',


    'update:pagado',

    'update:detalles_ventas',
])

const _nombre = computed({
    get() {
        return props.nombre
    },
    set(value) {
        emit('update:nombre', value)
    }
})

const _producto = computed({
    get() {
        return props.producto
    },
    set(value) {
        emit('update:producto', value)
    }
})

const _producto_options = computed({
    get() {
        return props.productos_options
    },
    set(value) {
        emit('update:productos_options', value)
    }
})

const _descripcion = computed({
    get() {
        return props.descripcion
    },
    set(value) {
        emit('update:descripcion', value)
    }
})

const _fecha = computed({
    get() {
        return props.fecha
    },
    set(value) {
        emit('update:fecha', value)
    }
})

const _pagado = computed({
    get() {
        return props.pagado
    },
    set(value) {
        emit('update:pagado', value)
    }
})

const _forma_pago = computed({
    get() {
        return props.forma_pago
    },
    set(value) {
        emit('update:forma_pago', value)
    }
})

const _formas_pago = computed({
    get() {
        return props.formas_pago
    },
    set(value) {
        emit('update:formas_pago', value)
    }
})

const _detalles_ventas = computed({
    get() {
        return props.detalles_ventas
    },
    set(value) {
        emit('update:detalles_ventas', value)
    }
})

const  detalles_ventas = ref([]);


const evtChangeProducto = ({selectProductoRef}) => {

    console.group("evtChangeProducto");

    const [ findProducto ] =  _producto_options.value.filter( option => option['selected']);

    if (findProducto) {
        const detalle = detalles_ventas.value.find( detalle => detalle.id_producto == findProducto.id);

        if (detalle) {
            const cantidad = Number(detalle['cantidad']) + 1;

            detalle['cantidad'] = Number(cantidad)
            detalle['total'] = cantidad * Number(detalle['precio']);
        }else{
            detalles_ventas.value.push({
                id_producto: findProducto.id,
                nombre:findProducto.nombre,
                descripcion: findProducto.descripcion,
                precio: findProducto.precio,
                imagen: findProducto.imagen ?? defaultProducto,
                cantidad: 1,
                total: findProducto.precio
            });
        }

        selectProductoRef.clear();
    }
    _detalles_ventas.value = [...detalles_ventas.value];

    console.groupEnd();
}

const evtClickLimpiarDetalles = () => {
    detalles_ventas.value = [];
    _detalles_ventas.value = [...detalles_ventas.value];
}

const evtClickEliminarDetalle = (index) => {
    detalles_ventas.value.splice(index,1)

    _detalles_ventas.value = [...detalles_ventas.value];
}

const evtClickAddQty = (producto,qty = 1) => {
    const cantidad = Number(producto.cantidad) + qty;

    producto.cantidad =  cantidad;
    producto.total = Number(producto.precio) *  cantidad ;
}

const evtClickSubQty = (producto,qty = 1) => {
    const cantidad = Number(producto.cantidad) - qty;

    if (cantidad > 0 ) {
        producto.cantidad =  cantidad;
        producto.total = Number(producto.precio) *  cantidad ;
    }
}
</script>

<template>

    <div class="form-group mb-4">
        <MDBInput
            class="active"
            type="text"
            v-model="_nombre"
            :class="{'is-invalid':props.nombre_errors}"
            :invalid-feedback="props.nombre_errors"
            label="Nombre"
        />
    </div>

    <div class="form-group mb-4">
        <MDBSelect
            searchPlaceholder="Buscar"
            noResultsText="Sin Resultados"
            selectAllLabel="Seleccionar todos"
            label="Producto"
            class="w-100"
            :optionHeight="60"
            :preselect="false"
            ref="selectProductoRef"
            clearButton
            filter
            v-model:selected="_producto"
            v-model:options="_producto_options"
            @change="evtChangeProducto($refs)"
        />
    </div>

    <section class="pb-4">
        <article  v-if="detalles_ventas.length > 0">
            <div class="d-flex justify-center justify-content-between align-content-center pb-2">
                <div>
                    <MDBBtn class="btn btn-sm" color="danger" @click="evtClickLimpiarDetalles">Limpiar</MDBBtn>
                </div>
                <div class="fw-bold">
                    <span>{{ _.sumBy(detalles_ventas,(detalle) => Number(detalle.cantidad))  }}</span> /
                    <span><i class="fas fa-dollar-sign"></i> {{ Intl.NumberFormat('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(_.sumBy(detalles_ventas,(detalle) => Number(detalle.total))) }}</span>
                </div>
            </div>


            <MDBListGroup light style="min-height: 5rem;overflow-y: auto;" >
                <template v-for="(detalle,index) in detalles_ventas" :key="index" >
                    <MDBListGroupItem class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                        <i
                            class="fa-lg fas fa-trash-alt me-2 text-danger"
                            title="Eliminar"
                            @click="evtClickEliminarDetalle(index)"
                            />
                        <img
                            :src="detalle.imagen"
                            :alt="`detalle_${detalle.nombre}_${index}`"
                            :title="detalle.nombre"
                            style="width: 45px; height: 45px"
                            class="rounded-circle"
                        />
                        <div class="ms-3">
                            <p class="fw-bold mb-1">{{ detalle.nombre }}  <span> $ {{ Intl.NumberFormat().format(detalle.precio) }}</span></p>
                            <p class="text-muted mb-0">{{ detalle.descripcion }}</p>
                            <p class="mb-0"> <i class="fa fa-circle-plus fa-lg text-primary me-2" @click="evtClickAddQty(detalle)" ></i> {{ detalle.cantidad }} <i class="ms-2 fa fa-circle-minus fa-lg text-primary" @click="evtClickSubQty(detalle)"></i>  </p>
                        </div>
                        </div>
                        <div>
                            {{ Intl.NumberFormat('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(detalle.total)  }}
                        </div>
                    </MDBListGroupItem>
                </template>
            </MDBListGroup>
        </article>

        <article v-else>
            <p class="text-muted"> Selecciona un producto para comenzar</p>
        </article>
    </section>

    <MDBRow class="mb-4">
        <MDBCol col="12">
            <MDBDatepicker
                v-model="_fecha"
                :class="{'is-invalid':props.fecha_errors}"
                :invalid-feedback="props.fecha_errors"
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
        <Editor v-model="_descripcion" editorStyle="height: 175px" />
    </div>

    <div class="form-group mb-4">
        <MDBSwitch
            label="Pagado"
            v-model="_pagado"
        />
    </div>

    <div class="form-group mb-4" v-if="_pagado">
        <MDBSelect
            searchPlaceholder="Buscar"
            noResultsText="Sin Resultados"
            label="Forma de pago"
            class="col-12"
            :preselect="false"
            :class="{'is-invalid':props.forma_pago_errors}"
            :invalid-feedback="props.forma_pago_errors"
            v-model:selected="_forma_pago"
            v-model:options="_formas_pago"
            filter
        />
    </div>
</template>
<style>
    .datepicker-toggle-button{
        right: 0;
    }
</style>

