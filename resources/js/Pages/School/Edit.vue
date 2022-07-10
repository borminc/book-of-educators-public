<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import EditSchoolForm from "./Partials/EditSchoolForm.vue";

const props = defineProps({
    school: Object,
});
</script>

<template>
    <AppLayout title="Edit school">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit school
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-5 px-8"
                >
                    <EditSchoolForm
                        :school="school"
                        submitButtonText="Save"
                        :redirect="route('work-experiences.index')"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
