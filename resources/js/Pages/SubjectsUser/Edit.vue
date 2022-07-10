<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import EditSubjectsForm from "./Partials/EditSubjectsForm.vue";

const props = defineProps({
    subjects: Array,
});
</script>

<template>
    <AppLayout title="Edit Subjects">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Subjects
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <EditSubjectsForm
                        :subjects="subjects"
                        submitButtonText="Save"
                        :redirect="route('work-experiences.index')"
                        class="p-4"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
