<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { Head } from "@inertiajs/inertia-vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import EditInstructorLevelForm from "./Partials/EditInstructorLevelForm.vue";

const props = defineProps({
    myInstructorLevels: Array,
    allInstructorLevels: Array,
});
</script>

<template>
    <Head title="Edit instructor level" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Instructor Level
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-5 px-8"
                >
                    <EditInstructorLevelForm
                        :myInstructorLevels="myInstructorLevels"
                        :instructorLevels="allInstructorLevels"
                        submitButtonText="Save"
                        :redirect="route('work-experiences.index')"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
