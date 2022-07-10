<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { Head } from "@inertiajs/inertia-vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { watch, ref, computed, onMounted } from "vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";

const props = defineProps({
    contact: Object,
});

const form = useForm({
    phone: "",
    email: "",
    address_1: "",
    address_2: "",
    ...props.contact,
    country_id: props.contact?.country_id ?? null,
    city_id: props.contact?.city_id ?? null,
});

// to submit: form.put(route('contacts.update'))
async function onSubmit(e) {
    // add
    form.put(route("contacts.update"));
}

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

onMounted(() => {
    getCountries();
    if (props.contact?.country_id) {
        getCities(props.contact.country_id);
    }
});
</script>

<template>
    <AppLayout title="Contact">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Contact
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3"
                >
                    <form @submit.prevent="onSubmit" class="m-3">
                        <JetValidationErrors class="mb-4" />
                        <div class="mt-4">
                            <JetLabel for="phone" value="Phone" required />
                            <JetInput
                                id="phone"
                                v-model="form.phone"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                            />
                        </div>
                        <div class="mt-4">
                            <JetLabel for="email" value="Email" required />
                            <JetInput
                                id="email"
                                v-model="form.email"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                            />
                        </div>
                        <div class="mt-4">
                            <JetLabel for="address_1" value="Address_1" />
                            <JetInput
                                id="address_1"
                                v-model="form.address_1"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                            />
                        </div>
                        <div class="mt-4">
                            <JetLabel for="address_2" value="Address_2" />
                            <JetInput
                                id="address_2"
                                v-model="form.address_2"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
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

                        <div class="flex justify-end items-center mt-4">
                            <JetActionMessage
                                :on="form.recentlySuccessful"
                                class="mr-3"
                            >
                                Saved.
                            </JetActionMessage>

                            <JetButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ contact ? "Save" : "Cancel" }}
                            </JetButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
