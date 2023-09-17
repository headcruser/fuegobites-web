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
import { reactive } from "vue";

import logotipo from '@/img/fuego-bites.png';
import defaultImage from '@/img/default-image.png'

import "primevue/resources/themes/bootstrap4-light-blue/theme.css";

import NavLink from '@/Components/NavLink.vue';
import MegaMenu from 'primevue/megamenu';
import Menu from "primevue/menu";


const sidenavMDB = ref(true);

const dropdown3 = ref(false);

const handleLinkClick = () => {
    const windowWidth = window.innerWidth;

    if (windowWidth < 1400) {
        sidenavMDB.value = false;
    }
};

const modelMenu = ref([
    {
        label: 'Administracion',
        icon: 'fas fa-cog fa-fw me-3',
        items: [
            [
                {
                    items: [
                        { label: 'Usuarios', icon:'fa fa-user',href: route('admin.users.index')},
                        { label: 'Roles',icon:'fas fa-user-group',href: route('admin.roles.index')},
                        { label: 'Permisos',icon:'fas fa-ruler',href: route('admin.perms.index')},
                        { label: 'Productos' ,icon:'fas fa-boxes-stacked',href: route('admin.productos.index')},
                    ]
                },
            ],
        ]
    },
    {
        label: 'Ventas',
        icon: 'fas fa-cash-register fa-fw me-3',
        items: [
            [
                {
                    items: [
                        { label: 'Registrar ventas', icon:'fa fa-user',href: route('ventas.registro.index')},
                    ]
                },
            ],
        ]
    }
]);

const items = ref([
    {
        label: 'Administración',
        items: [
            { label: 'Usuarios', icon:'fa fa-user',href: route('admin.users.index') },
            { label: 'Roles', icon: 'fas fa-user-group',href: route('admin.roles.index')},
            { label: 'Permisos',icon:'fas fa-ruler',href: route('admin.perms.index')},
            { label: 'Productos' ,icon:'fas fa-boxes-stacked',href: route('admin.productos.index')},
        ],
    },
    {separator:true},
    {
        label: 'Ventas',
        items: [
            { label: 'Registro de ventas', icon:'fa fa-cash-register',href: route('ventas.registro.index') },
        ],
    },
    {separator:true},
    {
        label: 'Opciones',
        items: [
            { label: 'Acerca de', icon:'fa fa-info-circle', href: route('about') },
        ],
    },
]);


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
                <div class="font-medium text-base text-gray-800 mb-2">{{ $page.props.auth.user.name }}</div>
                <div class="font-medium text-base text-gray-400 mb-2">{{ $page.props.auth.roles.join('') }}</div>
            </div>

            <div class="d-flex pb-3">
                <Menu :model="items" class="w-100 border-0">
                    <template #item="{ label, item, props }">
                    <a :href="item.href" v-bind="props.action">
                        <span v-bind="props.icon" />
                        <span v-bind="props.label">{{ label }}</span>
                    </a>
                </template>
                </Menu>
                <!-- <MegaMenu class="bg-white" :model="modelMenu" orientation="horizontal">
                    <template #item="{ label, item, props, hasSubmenu }">
                        <a :href="item.href" :target="item.target" v-bind="props.action" style="width: 100%;">
                            <span v-bind="props.icon" />
                            <span v-bind="props.label">{{ label }}</span>
                            <span :class="[hasSubmenu && 'pi pi-fw pi-angle-down']" v-bind="props.submenuicon" />
                        </a>
                    </template>
                </MegaMenu> -->
            </div>
<!--
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

                        <NavLink :href="route('admin.productos.index')" :active="route().current('admin.productos.*')"
                            @click="handleLinkClick">
                            <span>Productos</span>
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

            </MDBSideNavMenu> -->
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
