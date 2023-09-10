<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3'

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'sidenav-link ripple-surface active'
    : 'sidenav-link ripple-surface'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
