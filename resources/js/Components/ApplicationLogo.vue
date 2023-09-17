<script setup>
import logo from '@/img/fuego-bites.png';

import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const appName = computed(() =>  usePage().props.appName);
</script>

<template>
    <div>
        <img :src="logo" style="width: 10rem;height: 10rem;">
    </div>
    <div>
        <span class="text-muted"> {{ appName }} </span>
    </div>
</template>
