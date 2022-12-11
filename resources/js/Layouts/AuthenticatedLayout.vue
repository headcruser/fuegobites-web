<script setup>
    import SideNav from "@/Components/SideNav.vue";
</script>

<template>
    <SideNav />
    <!-- Page Content -->
    <main >
        <div
            v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert"
        >
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>
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
  </style>

