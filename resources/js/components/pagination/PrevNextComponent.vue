<script setup lang="ts">
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/24/solid";
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

defineProps({
    isPrev: {
        type: Boolean,
        required: true,
    },
    isNext: {
        type: Boolean,
        required: true,
    },
    link: {
        type: Object,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
})

const btnClassName = ref('relative inline-flex items-center px-2 py-2 text-gray-400 inset-ring inset-ring-gray-300 focus:z-20 focus:outline-offset-0')
</script>
<template>
    <Link
        v-if="link.page && isPrev"
        :href="url"
        :class="btnClassName"
        class="hover:bg-gray-50 rounded-l-md"
    >
        <ChevronLeftIcon class="size-5" />
    </Link>
    <span v-else-if="isPrev" :class="btnClassName" class="rounded-l-md">
        <ChevronLeftIcon class="size-5" />
    </span>

    <Link
        v-if="link.page && isNext"
        :href="url" :class="btnClassName"
        class="hover:bg-gray-50 rounded-r-md"
    >
        <ChevronRightIcon class="size-5"/>
    </Link>
    <span v-else-if="isNext" :class="btnClassName" class="rounded-r-md">
        <ChevronRightIcon class="size-5"/>
    </span>

</template>

<style scoped>

</style>
