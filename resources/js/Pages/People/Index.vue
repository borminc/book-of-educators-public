<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, watch, onMounted } from "vue";
import { computed } from "@vue/reactivity";
import Button from "../../Jetstream/Button.vue";
import { Inertia } from "@inertiajs/inertia";
import route from "../../../../vendor/tightenco/ziggy/src/js";
import Pagination from "../../Shared/Pagination.vue";
import axios from "axios";

const props = defineProps({
    people: {
        data: Array,
        links: Array,
    },
    subjects: Array,
    roles: Array,
    instructorLevels: Array,
    auth: {
        roles: Array,
    },
});

const filters = ref({
    search: "",
    subject_id: null,
    instructor_level_id: null,
    role_id: null,
    country_id: null,
    city_id: null,
    ...route().params,
});

function clearFilters() {
    filters.value.subject_id = null;
    filters.value.instructor_level_id = null;
    filters.value.role_id = null;
    filters.value.country_id = null;
    filters.value.city_id = null;
}

const filtersAreDirty = computed(() => {
    return (
        filters.value.subject_id !== null ||
        filters.value.instructor_level_id !== null ||
        filters.value.role_id !== null ||
        filters.value.country_id !== null ||
        filters.value.city_id !== null
    );
});

const debouncedOnApplyFilters = _.debounce(onApplyFilters, 200);

watch(
    filters,
    (value) => {
        debouncedOnApplyFilters();
    },
    {
        deep: true,
    }
);

const selectedRoleName = computed(() => {
    if (!filters.value.role_id) {
        return null;
    }
    const roles = props.roles.filter((r) => r.id == filters.value.role_id);
    return roles[0].name;
});

const showSearch = computed(() => props.auth?.roles?.includes("school"));
const showSubjectFilter = computed(() =>
    selectedRoleName.value ? selectedRoleName.value === "instructor" : true
);
const showInstructorLevelFilter = computed(() =>
    props.auth?.roles?.includes("school") && selectedRoleName.value
        ? selectedRoleName.value === "instructor"
        : true
);
const showTypeFilter = computed(() => true);
const showCountryFilter = computed(() => props.auth?.roles?.includes("school"));
const showCityFilter = computed(() => props.auth?.roles?.includes("school"));

function onApplyFilters() {
    if (!filters.value.search) {
        filters.value.search = null;
    }

    if (!showSubjectFilter.value) {
        filters.value.subject_id = null;
    }

    if (!showInstructorLevelFilter.value) {
        filters.value.instructor_level_id = null;
    }

    filters.value.page = null;

    const url = route(route().current(), filters.value);
    Inertia.get(
        url,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            only: ["people"],
        }
    );
}

// country and city filters
const selectedCountryId = computed({
    get: () => filters.value.country_id,
    set: (val) => {
        filters.value.country_id = val;
    },
});

const selectedCityId = computed({
    get: () => filters.value.city_id,
    set: (val) => {
        filters.value.city_id = val;
    },
});

const countries = ref([]);
const cities = ref([]);

watch(selectedCountryId, (value) => {
    if (selectedCountryId.value === null) {
        selectedCityId.value = null;
        cities.value = [];
        return;
    }

    getCities(value);
    selectedCityId.value = null;
});

async function getCountries() {
    const res = await axios.get(route("countries.index"));
    countries.value = res.data;
}

async function getCities(country_id) {
    const res = await axios.get(route("cities.index", { country_id }));
    cities.value = res.data;
}

const viewMode = ref(localStorage.getItem("viewMode") ?? "gallery");

watch(viewMode, (value) => {
    localStorage.setItem("viewMode", value);
});

onMounted(() => {
    getCountries();
    if (filters.value.country_id) {
        getCities(filters.value.country_id);
    }
});
</script>

<template>
    <AppLayout title="People">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    People
                </h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-3">
                    <div class="flex flex-col">
                        <div
                            class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-5"
                            v-if="showSearch"
                        >
                            <div v-if="showSearch">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Search</span
                                    >
                                </label>

                                <div class="relative rounded-md">
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-2"
                                        >
                                            <button
                                                type="submit"
                                                class="p-1 focus:outline-none focus:shadow-outline"
                                            >
                                                <svg
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    viewBox="0 0 24 24"
                                                    class="w-4 h-4"
                                                >
                                                    <path
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                    ></path>
                                                </svg>
                                            </button>
                                        </span>
                                        <input
                                            v-model="filters.search"
                                            type="search"
                                            name="search"
                                            class="w-full block mt-1 border-slate-200 rounded-md pl-8"
                                            placeholder="Search..."
                                            autocomplete="off"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid sm:grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4 mb-5"
                        >
                            <div v-if="showTypeFilter">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Account types</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="filters.role_id"
                                >
                                    <option :value="null">
                                        Select account type
                                    </option>
                                    <option
                                        v-for="role in roles"
                                        :key="role.id"
                                        :value="role.id"
                                    >
                                        {{ window._.capitalize(role.name) }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="showSubjectFilter">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Subject</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="filters.subject_id"
                                >
                                    <option :value="null">
                                        Select subject
                                    </option>
                                    <option
                                        v-for="sub in subjects"
                                        :key="sub.id"
                                        :value="sub.id"
                                    >
                                        {{ sub.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="showInstructorLevelFilter">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Instructor Levels</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="filters.instructor_level_id"
                                >
                                    <option :value="null">Select level</option>
                                    <option
                                        v-for="level in instructorLevels"
                                        :key="level.id"
                                        :value="level.id"
                                    >
                                        {{ `${level.level} - ${level.role}` }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="grid sm:grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4"
                            v-if="showCountryFilter || showCityFilter"
                        >
                            <div v-if="showCountryFilter">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Country</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="selectedCountryId"
                                >
                                    <option :value="null">
                                        Select country
                                    </option>
                                    <option
                                        v-for="country in countries"
                                        :key="country.id"
                                        :value="country.id"
                                    >
                                        {{ window._.capitalize(country.name) }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="showCityFilter">
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >Cities</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="selectedCityId"
                                    :disabled="!selectedCountryId"
                                >
                                    <option :value="null">Select city</option>
                                    <option
                                        v-for="city in cities"
                                        :key="city.id"
                                        :value="city.id"
                                    >
                                        {{ window._.capitalize(city.name) }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <button
                            @click="clearFilters"
                            class="self-start mt-5 items-center px-2 py-1 bg-gray-400 border border-transparent rounded-md text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition text-xs"
                            v-if="filtersAreDirty"
                        >
                            Clear filters
                        </button>

                        <div
                            class="grid sm:grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5"
                        >
                            <div>
                                <label
                                    class="block text-left"
                                    style="width: 200px; margin-right: 10px"
                                >
                                    <span class="text-sm text-gray-700"
                                        >View mode</span
                                    >
                                </label>
                                <select
                                    class="form-select block w-full mt-1 border-slate-200 rounded-md"
                                    v-model="viewMode"
                                    @change="(e) => (viewMode = e.target.value)"
                                >
                                    <option value="gallery">Gallery</option>
                                    <option value="list">List</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10" v-if="viewMode === 'gallery'">
                        <div
                            class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                        >
                            <h3
                                v-if="people.data?.length === 0"
                                class="text-xl"
                            >
                                No results found.
                            </h3>

                            <div
                                :key="person.id"
                                v-for="person in people.data"
                                class="p-4 rounded-lg bg-white shadow hover:shadow-2xl transition duration-300 ease-in-out"
                            >
                                <Link
                                    as="div"
                                    class="h-full flex flex-col justify-between hover:cursor-pointer"
                                    :href="
                                        route('people.show', {
                                            person: person.id,
                                        })
                                    "
                                >
                                    <div class="w-full text-center mb-5">
                                        <img
                                            class="inline rounded-full border-2 border-white shadow mb-2 img-gallery"
                                            :src="person.profile_photo_url"
                                            alt="profile image"
                                        />
                                        <h3 class="text-lg">
                                            {{ person.name }}
                                        </h3>

                                        <div>
                                            <small
                                                class="w-min bg-gray-100 px-2 rounded-lg mx-1"
                                                v-for="role in person.roles"
                                                :key="role.id"
                                            >
                                                {{
                                                    window._.capitalize(
                                                        role.name
                                                    )
                                                }}
                                            </small>
                                        </div>

                                        <div>
                                            <small
                                                class="bg-gradient-to-r from-indigo-200 to-indigo-100 px-2 rounded-lg"
                                            >
                                                <template
                                                    v-if="person.contact?.city"
                                                    >{{
                                                        person.contact?.city
                                                            ?.name
                                                    }},
                                                </template>
                                                <template
                                                    v-if="
                                                        person.contact?.country
                                                    "
                                                    >{{
                                                        person.contact?.country
                                                            ?.name
                                                    }}</template
                                                >
                                            </small>
                                        </div>

                                        <div class="mt-5">
                                            <p class="truncate text-sm">
                                                {{ person.title }}
                                            </p>
                                        </div>
                                    </div>

                                    <div v-if="person.contact" class="text-sm">
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
                                            <a
                                                :href="`tel:${person.contact?.phone}`"
                                                class="truncate hover:text-indigo-500"
                                                @click="
                                                    (e) => e.stopPropagation()
                                                "
                                                >{{ person.contact?.phone }}</a
                                            >
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
                                                :href="`mailto:${person.contact?.email}`"
                                                class="truncate hover:text-indigo-500"
                                                @click="
                                                    (e) => e.stopPropagation()
                                                "
                                            >
                                                {{ person.contact?.email }}
                                            </a>
                                        </div>
                                    </div>
                                    <div v-else>No contact info</div>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10" v-else>
                        <div>
                            <h3
                                v-if="people.data?.length === 0"
                                class="text-xl"
                            >
                                No results found.
                            </h3>
                            <div
                                class="relative overflow-x-auto bg-white rounded-md pb-3"
                                v-else
                            >
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="text-xs text-gray-400 uppercase"
                                    >
                                        <tr>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Account type</th>
                                            <th>Location</th>
                                            <th>Title</th>
                                            <th>Phone</th>
                                            <th>E-mail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            :key="person.id"
                                            v-for="person in people.data"
                                            class="divide-y divide-dashed"
                                        >
                                            <td>
                                                <img
                                                    class="rounded-lg img-list m-1"
                                                    :src="
                                                        person.profile_photo_url
                                                    "
                                                    alt="profile image"
                                                />
                                            </td>
                                            <td>
                                                <Link
                                                    as="div"
                                                    class="hover:cursor-pointer text-indigo-500"
                                                    :href="
                                                        route('people.show', {
                                                            person: person.id,
                                                        })
                                                    "
                                                >
                                                    {{ person.name }}
                                                </Link>
                                            </td>
                                            <td>
                                                <small
                                                    class="w-min bg-gray-100 rounded-lg px-2 text-sm mr-1"
                                                    v-for="role in person.roles"
                                                    :key="role.id"
                                                    :value="role.id"
                                                >
                                                    {{
                                                        window._.capitalize(
                                                            role.name
                                                        )
                                                    }}
                                                </small>
                                            </td>
                                            <td>
                                                <p>
                                                    <template
                                                        v-if="
                                                            person.contact?.city
                                                        "
                                                        >{{
                                                            person.contact?.city
                                                                ?.name
                                                        }},
                                                    </template>
                                                    <template
                                                        v-if="
                                                            person.contact
                                                                ?.country
                                                        "
                                                        >{{
                                                            person.contact
                                                                ?.country?.name
                                                        }}</template
                                                    >
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm">
                                                    {{ person.title }}
                                                </p>
                                            </td>
                                            <td>
                                                <a
                                                    :href="`tel:${person.contact?.phone}`"
                                                    class="hover:text-indigo-500"
                                                    @click="
                                                        (e) =>
                                                            e.stopPropagation()
                                                    "
                                                    >{{
                                                        person.contact?.phone
                                                    }}</a
                                                >
                                            </td>

                                            <td>
                                                <a
                                                    :href="`mailto:${person.contact?.email}`"
                                                    class="hover:text-indigo-500"
                                                    @click="
                                                        (e) =>
                                                            e.stopPropagation()
                                                    "
                                                >
                                                    {{ person.contact?.email }}
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div
                                :key="person.id"
                                v-for="person in people.data"
                                class="py-5 px-8 rounded-lg bg-white hover:shadow transition duration-300 ease-in-out"
                            >
                                <Link
                                    as="div"
                                    class="h-full flex flex-col justify-between hover:cursor-pointer"
                                    :href="
                                        route('people.show', {
                                            person: person.id,
                                        })
                                    "
                                >
                                    <div class="flex items-center">
                                        <img
                                            class="inline rounded-full border-2 border-white shadow mr-5 img flex-none align-middle"
                                            :src="person.profile_photo_url"
                                            alt="profile image"
                                        />
                                        <div class="ml-5">
                                            <div class="flex">
                                                <h3 class="text-lg">
                                                    {{ person.name }}
                                                </h3>
                                            </div>
                                            <div>
                                                <small
                                                    class="w-min bg-gray-100 px-2 rounded-lg mx-1"
                                                    v-for="role in person.roles"
                                                    :key="role.id"
                                                >
                                                    {{
                                                        window._.capitalize(
                                                            (
                                                                role.name
                                                            )
                                                        )
                                                    }}
                                                </small>
                                                <small
                                                    class="ml-2 bg-gradient-to-r from-indigo-200 to-indigo-100 px-2 rounded-lg"
                                                >
                                                    <template
                                                        v-if="
                                                            person.contact?.city
                                                        "
                                                        >{{
                                                            person.contact?.city
                                                                ?.name
                                                        }},
                                                    </template>
                                                    <template
                                                        v-if="
                                                            person.contact
                                                                ?.country
                                                        "
                                                        >{{
                                                            person.contact
                                                                ?.country?.name
                                                        }}</template
                                                    >
                                                </small>
                                            </div>
                                            <div class="mt-2 mb-2">
                                                <p class="truncate text-sm">
                                                    {{ person.title }}
                                                </p>
                                            </div>
                                            <div
                                                v-if="person.contact"
                                                class="text-sm"
                                            >
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
                                                    <a
                                                        :href="`tel:${person.contact?.phone}`"
                                                        class="truncate hover:text-indigo-500"
                                                        @click="
                                                            (e) =>
                                                                e.stopPropagation()
                                                        "
                                                        >{{
                                                            person.contact
                                                                ?.phone
                                                        }}</a
                                                    >
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
                                                        :href="`mailto:${person.contact?.email}`"
                                                        class="truncate hover:text-indigo-500"
                                                        @click="
                                                            (e) =>
                                                                e.stopPropagation()
                                                        "
                                                    >
                                                        {{
                                                            person.contact
                                                                ?.email
                                                        }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div v-else>No contact info</div>
                                        </div>
                                    </div>
                                </Link>
                            </div> -->
                        </div>
                    </div>

                    <Pagination class="mt-10" :links="people.links" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.img-gallery {
    width: 100px;
    height: 100px;
    object-fit: contain;
}

.img-list {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

th,
td {
    white-space: nowrap;
    padding: 0px 10px;
}

th {
    padding: 20px 10px 20px 10px;
}
</style>
