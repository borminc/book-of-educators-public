<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import route from "../../../../vendor/tightenco/ziggy/src/js";
import { watch, ref, onMounted } from "vue";
import axios from "axios";
import Button from "../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/Button.vue";
import BackButton from "../../Shared/BackButton.vue";
import EditSchoolForm from "../School/Partials/EditSchoolForm.vue";

const props = defineProps({
    school: Object,
});
</script>

<template>
    <Head title="Add school" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <h3 class="text-xl">School</h3>
        <small>Fill in the information of the school you represent.</small>

        <EditSchoolForm
            class="mt-4"
            :school="school"
            :redirect="route('registration.add-contact-view')"
        />

        <BackButton />
    </JetAuthenticationCard>
</template>
