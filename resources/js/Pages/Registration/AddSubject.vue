<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import route from "../../../../vendor/tightenco/ziggy/src/js";
import { watch, ref, onMounted } from "vue";
import axios from "axios";
import EditSubjectsForm from "../SubjectsUser/Partials/EditSubjectsForm.vue";
import BackButton from "../../Shared/BackButton.vue";

const props = defineProps({
    subjects: Array,
});
</script>

<template>
    <Head title="Add Subject" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <h3 class="text-xl">Subjects</h3>
        <small>Add the subjects that you teach.</small>

        <EditSubjectsForm
            class="mt-4"
            :subjects="subjects"
            :redirect="route('registration.add-contact-view')"
        />

        <BackButton />
    </JetAuthenticationCard>
</template>
