<script setup>
import { MDBAlert, MDBContainer } from "mdb-vue-ui-kit";
import SideNav from "@/Components/SideNav.vue";
</script>

<template>
    <SideNav />
    <!-- Page Content -->
    <main >
        <MDBContainer fluid class="pt-3" v-if="$page.props.flash.message">
            <MDBAlert color="success" :autohide="true" static dismiss>
                <i class="fas fa-check-circle me-3"></i>  {{ $page.props.flash.message }}
            </MDBAlert>
        </MDBContainer>
        <slot />
    </main>
  </template>


  <style>
  body {
    background-color: hsl(0, 0%, 97%);
  }

  @media (min-width: 1400px) {
    main,
    header,
    #main-navbar {
      padding-left: 240px;
    }
  }

  .text-light {
    color: #9fa6b2 !important;
  }

  .dropdown-toggle::after {
    display: none;
  }

  #main-navbar {
    padding-left: 0 !important;
  }

  .sidenav-backdrop{
    z-index:1034;top:0;left:0;background-color:rgba(0,0,0,.1);position:fixed !important;width:100%;height:100%;opacity:1;background-color:rgba(0,0,0,.1)
  }
  </style>

