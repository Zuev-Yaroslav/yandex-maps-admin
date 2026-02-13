<script setup lang="ts">

import AdminLayout from '@/layouts/AdminLayout.vue';

defineProps({
    errors: {
        required: true,
        type: Object,
    },
});
defineOptions({
    layout: AdminLayout,
})
</script>

<template>
    <p class="text-red-600 font-semibold">{{ errors.message }}</p>
</template>

<style scoped>

</style>
