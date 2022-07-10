<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import Button from "../Jetstream/Button.vue";
</script>

<template>
    <!-- <Link
        as="button"
        :href="window.localStorage.getItem('backUrl') ?? '/'"
        class="bg-gray-400 px-2 py-1"
    >
        Back
    </Link> -->
    <Button class="bg-gray-400 px-2 py-1" @click="window.history.back()">
        Back
    </Button>
</template>
