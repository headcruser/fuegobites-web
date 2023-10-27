<script setup>
import {
MDBSideNav,
MDBSideNavMenu,
MDBSideNavItem,
MDBIcon,
MDBNavbar,
MDBNavbarNav,
MDBNavbarToggler,
MDBDropdown,
MDBDropdownToggle,
MDBDropdownMenu,
} from "mdb-vue-ui-kit";

import { computed, ref } from "vue";

import logotipo from '@/img/fuego-bites.png';
import defaultImage from '@/img/default-image.png'

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";

import NavLink from '@/Components/NavLink.vue';
import { usePage } from "@inertiajs/vue3";

const userAuth = computed(() =>  usePage().props.auth.user);

const userImageProfile = computed(() => {
    return userAuth.value?.photo
        ? `/storage/users/${userAuth.value.id}/${userAuth.value.photo}`
        : defaultImage
})

const userPermissions = computed(() => Object.values(usePage().props.auth.permissions));

const sidenavMDB = ref(true);

const dropdown3 = ref(false);

const handleLinkClick = () => {
    const windowWidth = window.innerWidth;

    if (windowWidth < 1400) {
        sidenavMDB.value = false;
    }
}
</script>

<template>
    <!-- Main navigation -->
    <header>
        <!-- Sidenav-->
        <MDBSideNav v-model="sidenavMDB"
            id="sidenavMDB"
            tabindex="-1"
            contentSelector="#page-content"
            :modeBreakpoint="1400"
            :closeOnEsc="true">
            <div class="d-flex justify-content-center pb-4">
                <a :href="route('dashboard')">
                    <img :src="logotipo"
                        style="width: 5rem;height: 5rem;"
                        alt="Fuego bites"
                        draggable="false" />
                </a>
            </div>

            <div class="px-4 mb-2 text-center border-bottom">
                <div class="mb-2 text-muted">{{ userAuth.name }}</div>
                <div class="fw-bold mb-2 text-capitalize">{{ $page.props.auth.roles.join('') }}</div>
            </div>


            <MDBSideNavMenu>
                <MDBSideNavItem>
                    <NavLink
                        :href="route('admin.users.index')"
                        :active="route().current('admin.users.*')"
                        v-if="userPermissions.includes('gestionar_usuarios')"
                        @click="handleLinkClick">
                        <MDBIcon icon="user" class="fa-fw me-3"></MDBIcon>
                        <span>Usuarios</span>
                    </NavLink>
                </MDBSideNavItem>

                <MDBSideNavItem>
                    <NavLink
                        :href="route('admin.roles.index')"
                        :active="route().current('admin.roles.*')"
                        v-if="userPermissions.includes('gestionar_roles')"
                        @click="handleLinkClick">
                        <MDBIcon icon="building" class="fa-fw me-3"></MDBIcon>
                        <span>Roles</span>
                    </NavLink>

                </MDBSideNavItem>

                <MDBSideNavItem>
                    <NavLink :href="route('admin.perms.index')" :active="route().current('admin.perms.*')"
                        v-if="userPermissions.includes('gestionar_permisos')"
                        @click="handleLinkClick">
                        <MDBIcon icon="lock-open" class="fa-fw me-3"></MDBIcon>
                        <span>Permisos</span>
                    </NavLink>
                </MDBSideNavItem>
                <MDBSideNavItem>
                    <NavLink
                        v-if="userPermissions.includes('gestionar_productos')"
                        :href="route('admin.productos.index')"
                        :active="route().current('admin.productos.*')"
                        @click="handleLinkClick">
                        <MDBIcon icon="box" class="fa-fw me-3"></MDBIcon>
                        <span>Productos</span>
                    </NavLink>
                </MDBSideNavItem>

                <hr>

                <MDBSideNavItem>
                    <NavLink
                        v-if="userPermissions.includes('registrar_pedido')"
                        :href="route('ventas.registro.index')"
                        :active="route().current('ventas.registro.*')"
                        @click="handleLinkClick">
                        <MDBIcon icon="book" class="fa-fw me-3"></MDBIcon>
                        <span>Registrar pedido</span>
                    </NavLink>
                </MDBSideNavItem>


                <MDBSideNavItem>
                    <NavLink
                        v-if="userPermissions.includes('pedidos_por_hacer')"
                        :href="route('ventas.pedidos.index')"
                        :active="route().current('ventas.pedidos.*')"
                        @click="handleLinkClick">
                        <MDBIcon icon="industry" class="fa-fw me-3"></MDBIcon>
                        <span>Pedidos por hacer</span>
                    </NavLink>
                </MDBSideNavItem>

                <MDBSideNavItem>
                    <NavLink
                        v-if="userPermissions.includes('reporte_mensual')"
                        :href="route('ventas.reporte.index')"
                        :active="route().current('ventas.reporte.*')"
                        @click="handleLinkClick">
                        <MDBIcon icon="chart-line" class="fa-fw me-3"></MDBIcon>
                        <span>Reporte Ventas</span>
                    </NavLink>
                </MDBSideNavItem>

                <hr />

                <MDBSideNavItem>
                    <NavLink :href="route('about')" :active="route().current('about')" @click="handleLinkClick">
                        <MDBIcon icon="info-circle" class="fa-fw me-3"></MDBIcon>
                        <span>Acerca de </span>
                    </NavLink>
                </MDBSideNavItem>
            </MDBSideNavMenu>
        </MDBSideNav>
        <!-- Sidenav-->

        <!-- Navbar-->
        <MDBNavbar expand="lg" light bg="light" container id="main-navbar" >
            <!-- Toggler -->
            <MDBNavbarToggler @click="sidenavMDB = !sidenavMDB" target="#sidenavMDB">
                <MDBIcon icon="bars" />
            </MDBNavbarToggler>

            <!-- Right links -->
            <MDBNavbarNav right class="d-flex flex-row">
                <MDBDropdown v-model="dropdown3" class="nav-item me-3 me-lg-0">
                    <MDBDropdownToggle tag="a" class="nav-link" @click="dropdown3 = !dropdown3">
                        <img
                          :src="userImageProfile"
                          class="rounded-circle"
                          style="width: 25px;"
                          loading="lazy" />
                    </MDBDropdownToggle>

                    <MDBDropdownMenu>
                        <NavLink class="dropdown-item" :href="route('profile')" as="button">
                            Perfil
                        </NavLink>
                        <NavLink class="dropdown-item" :href="route('logout')" method="post" as="button">
                            Cerrar sesión
                        </NavLink>
                        <!-- <MDBDropdownItem >Cerrar sesión</MDBDropdownItem> -->
                    </MDBDropdownMenu>
                </MDBDropdown>
            </MDBNavbarNav>
        </MDBNavbar>
        <!-- Navbar-->
    </header>
    <!-- Main navigation -->
</template>

<style>
.page-intro {
    background-size: cover;
    background-position-x: center;
    background-color: white;
    width: 100vw;
    height: 100vh;
}

@media (min-width: 1400px) {

    main,
    #main-navbar {
        padding-left: 240px;
    }
}

.p-megamenu-root-list > .p-menuitem {
    position: relative;
    width: 100% !important;
}
</style>
