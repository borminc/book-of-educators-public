<script setup>
defineProps({
    value: String,
    required: Boolean,
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-if="required" class="text-red-500">*</span>
        <span v-else><slot /></span>
    </label>
</template>
