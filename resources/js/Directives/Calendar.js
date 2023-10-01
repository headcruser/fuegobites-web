import moment from "moment";

const formatCalendar = (el,venta) => {
    const fecha = moment(venta.value.fecha);
    const fechaActual = moment();

    el.innerHTML = `Entrega ${fecha.calendar()} `

    if (fecha.isSame(fechaActual,'day')) {
        el.classList.remove('text-danger');
        el.classList.remove('text-muted');
        el.classList.add('text-primary');
    }else{
        if (fecha.isBefore(fechaActual,'day')) {
            el.classList.remove('text-muted');
            el.classList.remove('text-primary');
            el.classList.add('text-danger');
        }else{
            if (fecha.isAfter(fechaActual,'day')) {
                el.classList.remove('text-primary');
                el.classList.remove('text-danger');
                el.classList.add('text-muted');
            }
        }
    }

}

const vFechaEntrega = {
    beforeMount: formatCalendar,
    updated: formatCalendar,
}

export default vFechaEntrega;
