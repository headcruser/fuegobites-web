<script setup>
import {
  MDBContainer,
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBCardFooter,
  MDBInput,
  MDBBtn,
  MDBIcon,
  MDBTable,
  MDBRow,
  MDBCol,
  MDBCardTitle,
} from "mdb-vue-ui-kit";

import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  users: Object,
  filters: Object,
});

const form = useForm();
const search = ref(props.filters.search);
const perPage = ref(10);

const createDebounce = () => {
  let timeout = null;
  return function (fnc, delayMs) {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      fnc();
    }, delayMs || 500);
  };
};

watch(search, (value) => {
  const debouncer = createDebounce();

  debouncer(() => {
    Inertia.get(
      route("admin.users.index"),
      {
        search: value,
        perPage: perPage.value,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  });
});

const destroy = (id) => {
  Swal.fire({
    title: "¿Deseas eliminar el registro?",
    text: "Se eliminara el registro!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route("admin.users.destroy", id), {
        onSuccess: () => {
          Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success");
        },
      });
    }
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="mt-4 mx-4">
     <h4 class="mb-0">Usuarios</h4>

      <div class="d-flex justify-content-end mb-4">
        <div class="form-outline">
          <MDBInput v-model="search" label="Buscar" />
        </div>

        <Link
          as="button"
          type="button"
          className="btn btn-primary btn-sm ms-3 ripple-surface"
          :href="route('admin.users.create')"
        >
          <MDBIcon icon="plus"></MDBIcon>
        </Link>
      </div>

      <MDBRow>
        <template v-for="user in users.data" :key="user.id">
          <MDBCol sm="4">
            <MDBCard>
              <MDBCardBody class="text-center">
                <img
                  class="rounded-circle shadow-1 mb-3 d-block"
                  src="../../../img/default-image.png"
                  alt="avatar"
                  style="width: 45px; margin: auto; display: block"
                />

                <MDBCardTitle>{{ user.name }}</MDBCardTitle>

                <MDBCardText>
                  <a :href="`mailto:${user.email}`">{{ user.email }}</a>
                  <p>{{ user.name_role_user }}</p>
                </MDBCardText>
              </MDBCardBody>
              <MDBCardFooter class="d-flex justify-content-center">
                <Link
                    tabIndex="1"
                    className="btn btn-small btn-outline-primary btn-floating ripple-surface"
                    as="button"
                    :href="route('admin.users.edit', user.id)"
                >
                    <MDBIcon icon="pencil"></MDBIcon>
                </Link>

                <MDBBtn outline="danger"
                    title="Eliminar"
                    size="small"
                    floating
                    @click="destroy(user.id)"
                >
                    <MDBIcon icon="trash"></MDBIcon>
                </MDBBtn>
              </MDBCardFooter>
            </MDBCard>
          </MDBCol>
        </template>
      </MDBRow>
    </div>
  </AuthenticatedLayout>
</template>
