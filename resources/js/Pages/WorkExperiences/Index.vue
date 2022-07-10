<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    instructorLevels: Array,
    workExperiences: Array,
    subjects: Array,
    user: Object,
});

const userRoles = props.user.roles.map((r) => r.name);
</script>

<template>
    <AppLayout title="Work Experiences">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Work
                </h2>
            </div>
        </template>

        <div v-if="userRoles.includes('school') && user.school" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Instructor Levels -->
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <div class="flex justify-between m-5 mb-10">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            School
                        </h2>
                        <Link
                            :href="route('school-user.edit')"
                            class="inline-flex items-center px-3 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >
                            Edit
                        </Link>
                    </div>

                    <div class="ml-5 mb-2">
                        <span
                            class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                        >
                            {{ user.school.user_role_in_school }}
                            at {{ user.school.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="userRoles.includes('instructor')"
            :class="{
                'py-12': !(userRoles.includes('school') && user.school),
            }"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Instructor Levels -->
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <div class="flex justify-between m-5 mb-10">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            Instructor Levels
                        </h2>
                        <Link
                            :href="route('instructor-levels.edit')"
                            class="inline-flex items-center px-3 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >
                            Edit
                        </Link>
                    </div>

                    <div class="ml-5">
                        <ul class="flex">
                            <li
                                v-for="il in instructorLevels"
                                :key="il.id"
                                class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                            >
                                {{ `${il.level} - ${il.role}` }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Subjects -->
                <div
                    class="mt-10 bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <div class="flex justify-between m-5 mb-10">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            Subjects
                        </h2>
                        <Link
                            :href="route('subject-user.edit')"
                            class="inline-flex items-center px-3 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >
                            Edit
                        </Link>
                    </div>

                    <div class="ml-5">
                        <ul class="flex">
                            <li
                                v-for="sub in subjects"
                                :key="sub.id"
                                class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                            >
                                {{ window._.capitalize(sub.name) }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Work experiences -->
                <div
                    class="mt-10 bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <div class="flex justify-between m-5 mb-10">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            Work Experiences
                        </h2>
                        <Link
                            :href="route('work-experiences.create')"
                            class="inline-flex items-center px-3 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >
                            Add
                        </Link>
                    </div>

                    <ul v-if="workExperiences && workExperiences.length > 0">
                        <li
                            v-for="we in workExperiences"
                            :key="we.id"
                            class="m-5 pl-3 border-l-4 border-gray-200"
                        >
                            <div class="mb-1 flex justify-between">
                                <small>
                                    <span>{{
                                        formatDate(we.start_date, "-")
                                    }}</span>
                                    <span>
                                        -
                                        {{
                                            formatDate(we.end_date, "Present")
                                        }}</span
                                    >
                                </small>

                                <Link
                                    v-if="user?.id === we.user_id"
                                    :href="
                                        route('work-experiences.edit', {
                                            work_experience: we.id,
                                        })
                                    "
                                    ><small
                                        class="text-gray-600 hover:text-gray-900"
                                        >edit</small
                                    ></Link
                                >
                            </div>

                            <div class="mb-3">
                                <Link
                                    :href="
                                        route('work-experiences.show', {
                                            work_experience: we.id,
                                        })
                                    "
                                >
                                    <h3
                                        class="text-xl font-bold hover:text-indigo-500"
                                    >
                                        {{ we.title }}
                                    </h3>
                                </Link>
                                <h5>{{ we.company }}</h5>
                            </div>

                            <p class="text-gray truncate">
                                <small>{{ we.description }}</small>
                            </p>
                        </li>
                    </ul>
                    <p v-else class="pl-5">
                        Add some work experiences to enrich your work profile!
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
