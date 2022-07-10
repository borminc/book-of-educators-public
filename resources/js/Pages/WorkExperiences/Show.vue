<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    workExperience: Object,
    user: Object,
});
</script>

<template>
    <AppLayout title="Work Experiences">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Work Experience
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <div class="m-5 pl-3 border-l-4 border-gray-200">
                        <div class="mb-1 flex justify-between">
                            <small>
                                <span>{{
                                    formatDate(workExperience.start_date, "-")
                                }}</span>
                                <span>
                                    -
                                    {{
                                        formatDate(
                                            workExperience.end_date,
                                            "Present"
                                        )
                                    }}</span
                                >
                            </small>

                            <Link
                                v-if="user?.id === workExperience.user_id"
                                :href="
                                    route('work-experiences.edit', {
                                        work_experience: workExperience.id,
                                    })
                                "
                                ><small
                                    class="text-gray-600 hover:text-gray-900"
                                    >edit</small
                                ></Link
                            >
                        </div>

                        <div class="mb-3">
                            <h3 class="text-xl font-bold">
                                {{ workExperience.title }}
                            </h3>
                            <h5>{{ workExperience.company }}</h5>
                        </div>

                        <small class="text-gray">
                            {{ workExperience.description }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
