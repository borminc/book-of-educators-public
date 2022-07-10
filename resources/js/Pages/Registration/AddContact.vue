<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import route from "../../../../vendor/tightenco/ziggy/src/js";
import { watch, ref, computed, onMounted } from "vue";
import axios from "axios";
import BackButton from "../../Shared/BackButton.vue";

const props = defineProps({
    contact: Object,
});

const form = useForm({
    phone: props.contact?.phone ?? "",
    email: props.contact?.email ?? "",
    address_1: props.contact?.address_1 ?? "",
    address_2: props.contact?.address_2 ?? "",
    city_id: props.contact?.city_id ?? null,
    country_id: props.contact?.country_id ?? null,
});

const selectedCountryId = computed({
    get: () => form.country_id,
    set: (val) => {
        form.country_id = val;
    },
});

const selectedCityId = computed({
    get: () => form.city_id,
    set: (val) => {
        form.city_id = val;
    },
});

const countries = ref([]);
const cities = ref([]);

watch(selectedCountryId, (value) => {
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

function onSubmit(e) {
    form.post(route("registration.add-contact"));
}

onMounted(() => {
    getCountries();
    if (props.contact?.country_id) {
        getCities(props.contact.country_id);
    }
});
</script>

<template>
    <Head title="Register" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <JetValidationErrors class="mb-4" />

        <h3 class="text-xl">Contact information</h3>
        <small>This will be visible to people looking at your profile.</small>

        <form class="mt-4" @submit.prevent="onSubmit">
            <div class="mt-4">
                <JetLabel for="phone" value="Phone" />
                <JetInput
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="phone"
                />
            </div>

            <div class="mt-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="email"
                />
            </div>

            <div class="mt-4">
                <JetLabel for="address_1" value="Address (line 1)" />
                <JetInput
                    id="address_1"
                    v-model="form.address_1"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="address"
                />
            </div>

            <div class="mt-4">
                <JetLabel for="address_2" value="Address (line 2)" />
                <JetInput
                    id="address_2"
                    v-model="form.address_2"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="address"
                />
            </div>

            <div class="mt-4">
                <JetLabel for="countries" value="Country" />
                <select
                    id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    v-model="form.country_id"
                >
                    <option :value="null">Choose a country</option>
                    <option
                        v-for="country in countries"
                        :key="country.id"
                        :value="country.id"
                    >
                        {{ country.name }}
                    </option>
                </select>
            </div>

            <div class="mt-4">
                <JetLabel for="city" value="City" />
                <select
                    id="cities"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    v-model="selectedCityId"
                >
                    <option :value="null">Choose a city</option>
                    <option
                        v-for="city in cities"
                        :key="city.id"
                        :value="city.id"
                    >
                        {{ city.name }}
                    </option>
                </select>
            </div>

            <div class="flex items-center justify-end mt-4">
                <JetButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Continue
                </JetButton>
            </div>
        </form>

        <BackButton />
    </JetAuthenticationCard>
</template>
