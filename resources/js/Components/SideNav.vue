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

import { ref } from "vue";

import logotipo from '@/img/fuego-bites.png';
import defaultImage from '@/img/default-image.png'

import NavLink from '@/Components/NavLink.vue';

const sidenavMDB = ref(true);

const dropdown3 = ref(false);

const handleLinkClick = () => {
    const windowWidth = window.innerWidth;

    if (windowWidth < 1400) {
        sidenavMDB.value = false;
    }
};
</script>

<template>
    <!-- Main navigation -->
    <header>
        <!-- Sidenav-->
        <MDBSideNav v-model="sidenavMDB"
            id="sidenavMDB"
            contentSelector="#page-content" :modeBreakpoint="1400"
            :closeOnEsc="true">
            <div class="d-flex justify-content-center pb-4">
                <a :href="route('dashboard')">
                    <img :src="logotipo"
                        style="width: 10rem;height: 10rem;"
                        alt="Fuego bites"
                        draggable="false" />
                </a>
            </div>

            <div class="px-4 mb-2 text-center border-bottom">
                <div class="font-medium text-base text-gray-800 mb-2">{{ $page.props.auth.user.name }}</div>
                <div class="font-medium text-base text-gray-400 mb-2">{{ $page.props.auth.roles.join('') }}</div>
            </div>
            <MDBSideNavMenu accordion>
                <MDBSideNavItem collapse icon="cog" title="Administración" :show="route().current('admin.*')" >
                    <MDBSideNavItem>
                        <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')"
                            @click="handleLinkClick">
                            <span>Usuarios</span>
                        </NavLink>

                        <NavLink :href="route('admin.roles.index')" :active="route().current('admin.roles.*')"
                            @click="handleLinkClick">
                            <span>Roles</span>
                        </NavLink>

                        <NavLink :href="route('admin.perms.index')" :active="route().current('admin.perms.*')"
                            @click="handleLinkClick">
                            <span>Permisos</span>
                        </NavLink>
                    </MDBSideNavItem>
                </MDBSideNavItem>


                <MDBSideNavItem collapse
                    :show="route().current('ventas.registro.*')"
                    icon="cash-register"
                    title="Ventas">
                    <MDBSideNavItem>
                        <NavLink
                            :href="route('ventas.registro.index')"
                            :active="route().current('ventas.registro.*')"
                            @click="handleLinkClick">
                            <span>Registrar ventas</span>
                        </NavLink>
                    </MDBSideNavItem>
                </MDBSideNavItem>

                <hr />

                <MDBSideNavItem>
                    <NavLink :href="route('about')" :active="route().current('about')" @click="handleLinkClick">
                        <MDBIcon icon="info-circle" class="fa-fw me-3"></MDBIcon>
                        <span>Acerca de </span>
                    </NavLink>
                </MDBSideNavItem>

                <MDBSideNavItem>
                    <NavLink :href="route('logout')" method="post" title="Cerrar sesión" @click="handleLinkClick">
                        <MDBIcon icon="arrow-right-from-bracket" class="me-3"></MDBIcon>
                        <span>Cerrar sesión</span>
                    </NavLink>
                </MDBSideNavItem>

            </MDBSideNavMenu>
        </MDBSideNav>
        <!-- Sidenav-->

        <!-- Navbar-->
        <MDBNavbar expand="lg" light bg="light" container id="main-navbar">
            <!-- Toggler -->
            <MDBNavbarToggler @click="sidenavMDB = !sidenavMDB" target="#sidenavMDB">
                <MDBIcon icon="bars" />
            </MDBNavbarToggler>


            <!-- Right links -->
            <MDBNavbarNav right class="d-flex flex-row">
                <MDBDropdown v-model="dropdown3" class="nav-item me-3 me-lg-0">
                    <MDBDropdownToggle tag="a" class="nav-link" @click="dropdown3 = !dropdown3">
                        <img
                          :src="$page.props.auth.user?.photo ? `/storage/users/${$page.props.auth.user.id}/${$page.props.auth.user.photo}`: defaultImage"
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



<style scoped>
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
</style>
