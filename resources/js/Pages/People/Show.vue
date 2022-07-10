<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    person: Array,
    user: Object,
});
</script>

<template>
    <AppLayout title="People">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ person.name }}
                </h2>
            </div>
        </template>

        <div class="pb-6">
            <div class="my-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                    >
                        <!-- <div class="flex justify-between m-5 mb-10">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight"
                        >
                            Educator Account
                        </h2>
                    </div> -->

                        <div class="flex items-center space-x-4 m-5">
                            <img
                                class="w-20 h-20 rounded-lg shadow img"
                                :src="person.profile_photo_url"
                                alt="profile"
                            />
                            <div class="space-y-1 font-medium">
                                <div class="flex items-center">
                                    <h3 class="text-lg mr-2">
                                        {{ person.name }}
                                    </h3>

                                    <small
                                        class="text-sm w-min h-min bg-gray-100 px-2 rounded-lg mx-1"
                                        v-for="role in person.roles"
                                        :key="role.id"
                                    >
                                        {{ window._.capitalize(role.name) }}
                                    </small>
                                </div>

                                <div class="text-sm text-gray-500">
                                    Joined since
                                    {{ formatDate(person.created_at, "-") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mb-6"
                v-if="
                    person.roles.length > 0 ||
                    person.instructor_levels?.length > 0 ||
                    person.subjects?.length > 0
                "
            >
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5"
                    >
                        <div class="flex items-center space-x-4">
                            <div class="font-medium">
                                <div
                                    class="flex justify-between m-5"
                                    :class="{
                                        'mb-0': !(
                                            person.instructor_levels?.length > 0
                                        ),
                                    }"
                                    v-if="person.school"
                                >
                                    <div
                                        class="font-semibold text-xl text-gray-800 leading-tight"
                                    >
                                        <div class="flex">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    d="M12 14l9-5-9-5-9 5 9 5z"
                                                />
                                                <path
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                                                />
                                            </svg>
                                            <div>School</div>
                                        </div>
                                        <div class="my-6">
                                            <small
                                                class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                                            >
                                                {{
                                                    person.school
                                                        .user_role_in_school
                                                }}
                                                at {{ person.school.name }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex justify-between m-5"
                                    v-if="person.instructor_levels?.length > 0"
                                >
                                    <h2
                                        class="font-semibold text-xl text-gray-800 leading-tight"
                                    >
                                        <div class="flex">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            <div>Instructor Levels</div>
                                        </div>
                                        <div class="my-6">
                                            <div class="flex">
                                                <ul
                                                    v-for="instructor_level in person.instructor_levels"
                                                    :key="instructor_level.id"
                                                >
                                                    <li
                                                        class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                                                    >
                                                        <small>{{
                                                            `${instructor_level.level} - ${instructor_level.role}`
                                                        }}</small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </h2>
                                </div>
                                <div
                                    class="flex justify-between m-5 mb-0"
                                    v-if="person.subjects?.length > 0"
                                >
                                    <div
                                        class="font-semibold text-xl text-gray-800 leading-tight"
                                    >
                                        <div class="flex">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                                />
                                            </svg>
                                            <div>Subjects</div>
                                        </div>
                                        <div class="my-6">
                                            <div class="flex">
                                                <ul
                                                    v-for="subject in person.subjects"
                                                    :key="subject.id"
                                                >
                                                    <li
                                                        class="p-1 m-1 bg-gradient-to-r from-indigo-200 to-indigo-100 hover:from-indigo-300 hover:to-indigo-200 px-2 rounded-lg"
                                                    >
                                                        <small>{{
                                                            window._.capitalize(
                                                                subject.name
                                                            )
                                                        }}</small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6" v-if="person.contact">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5"
                    >
                        <div class="flex justify-between m-5 mb-10">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight"
                            >
                                Contact
                            </h2>
                        </div>

                        <div class="flex items-center space-x-4 ml-5">
                            <div class="space-y-1 font-medium">
                                <div class="flex">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M14.414 7l3.293-3.293a1 1 0 00-1.414-1.414L13 5.586V4a1 1 0 10-2 0v4.003a.996.996 0 00.617.921A.997.997 0 0012 9h4a1 1 0 100-2h-1.586z"
                                        />
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                                        />
                                    </svg>
                                    <a :href="`tel:${person.contact.phone}`">{{
                                        person.contact.phone
                                    }}</a>
                                </div>
                                <div class="flex">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <a
                                        :href="`mailto:${person.contact.email}`"
                                        >{{ person.contact.email }}</a
                                    >
                                </div>

                                <div
                                    class="flex"
                                    v-if="
                                        person.contact.address_1 ||
                                        person.contact.address_2
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                        />
                                    </svg>
                                    <ul>
                                        <li v-if="person.contact.address_1">
                                            <div>
                                                {{ person.contact.address_1 }}
                                            </div>
                                        </li>
                                        <li v-if="person.contact.address_2">
                                            <div>
                                                {{ person.contact.address_2 }}
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div
                                    class="flex"
                                    v-if="
                                        person.contact.city ||
                                        person.contact.country
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-2"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <div>
                                        {{ person.contact.city.name }},
                                        {{ person.contact.country.name }}
                                    </div>
                                </div>

                                <div class="text-sm text-gray-500 pt-5">
                                    Last updated in
                                    {{
                                        formatDate(
                                            person.contact.created_at,
                                            "-"
                                        )
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6" v-if="person.work_experiences?.length > 0">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                    >
                        <div class="flex justify-between m-5 mb-10">
                            <h2
                                class="font-semibold text-xl text-gray-800 leading-tight"
                            >
                                Work Experiences
                            </h2>
                        </div>
                        <ul>
                            <li
                                v-for="we in person.work_experiences"
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
                                                formatDate(
                                                    we.end_date,
                                                    "Present"
                                                )
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
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.img {
    width: 100px;
    height: 100px;
    object-fit: contain;
}
</style>
