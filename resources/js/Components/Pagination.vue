<script setup>
import { MDBPagination, MDBPageNav, MDBPageItem } from 'mdb-vue-ui-kit';

defineProps({
    links: {
        type: Object,
        default: () => ({}),
    },
});
</script>


<template>
    <nav aria-label="Paginación">
        <MDBPagination class="justify-content-end">
            <template v-for="(link, k) in links"  :key="k">
                <MDBPageNav  prev icon v-if="link.label.includes('laquo')" :href="link.url"></MDBPageNav>
                <MDBPageItem v-if="!link.label.includes('&')" :href="link.url"> {{ link.label }}</MDBPageItem>
                <MDBPageNav v-if="link.label.includes('raquo')" next icon :href="link.url"></MDBPageNav>
            </template>
        </MDBPagination>
    </nav>
</template>
