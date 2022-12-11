<template>
    <!-- Main navigation -->
    <header>
      <!-- Sidenav-->
      <MDBSideNav
        v-model="sidenavMDB"
        id="sidenavMDB"
        contentSelector="#page-content"
        :modeBreakpoint="1400"
        :closeOnEsc="true"
      >
        <div class="d-flex justify-content-center py-4">
            <img
                id="MDB-logo"
                src="https://mdbootstrap.com/wp-content/uploads/2018/06/logo-mdb-jquery-small.png"
                alt="MDB Logo"
                draggable="false"
            />
        </div>

        <div class="px-4 mb-2 text-center border-bottom">
            <div class="font-medium text-base text-gray-800 mb-2">{{ $page.props.auth.user.name }}</div>
            <div class="font-medium text-base text-gray-400 mb-2">{{ $page.props.auth.roles.join('') }}</div>
        </div>
        <MDBSideNavMenu accordion>
            <MDBSideNavItem>
                <NavLink :href="route('dashboard')"  :active="route().current('dashboard')" @click="handleLinkClick">
                    <MDBIcon icon="home" class="fa-fw me-3"></MDBIcon>
                    <span>Inicio</span>
                </NavLink>
            </MDBSideNavItem>


            <MDBSideNavItem collapse icon="cog" title="Administración" >
                <MDBSideNavItem >
                    <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')" @click="handleLinkClick">
                        <span>Usuarios</span>
                    </NavLink>

                    <NavLink :href="route('admin.roles.index')" :active="route().current('admin.roles.*')" @click="handleLinkClick">
                        <span>Roles</span>
                    </NavLink>

                    <NavLink :href="route('admin.perms.index')" :active="route().current('admin.perms.*')" @click="handleLinkClick">
                        <span>Permisos</span>
                    </NavLink>
                </MDBSideNavItem>
            </MDBSideNavItem>

            <hr />

            <MDBSideNavItem>
                <NavLink :href="route('about')"  :active="route().current('about')" @click="handleLinkClick">
                    <MDBIcon icon="info-circle" class="fa-fw me-3"></MDBIcon>
                    <span>Acerca de </span>
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
            <MDBDropdown
                v-model="dropdown3"
                class="nav-item me-3 me-lg-0"
            >
                <MDBDropdownToggle
                    tag="a"
                    class="nav-link"
                    @click="dropdown3 = !dropdown3"
                >
                    <img
                    src="https://via.placeholder.com/30"
                    class="rounded-circle"
                    height="22"
                    alt=""
                    loading="lazy"
                    />
                </MDBDropdownToggle>

                <MDBDropdownMenu>
                    <MDBDropdownItem href="#">Perfil</MDBDropdownItem>
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

  <script setup>
  import {
    MDBSideNav,
    MDBSideNavMenu,
    MDBSideNavItem,
    MDBSideNavLink,
    MDBIcon,
    MDBNavbar,
    MDBNavbarNav,
    MDBNavbarToggler,
    MDBDropdown,
    MDBDropdownToggle,
    MDBDropdownMenu,
    MDBDropdownItem,

  } from "mdb-vue-ui-kit";

  import { ref } from "vue";

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
