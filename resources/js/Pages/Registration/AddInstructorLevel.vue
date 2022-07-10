<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import EditInstructorLevelForm from "../InstructorLevels/Partials/EditInstructorLevelForm.vue";
import BackButton from "../../Shared/BackButton.vue";

const props = defineProps({
    instructorLevels: Array,
    myInstructorLevels: Array,
});
</script>

<template>
    <Head title="Instructor Level" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <JetValidationErrors class="mb-4" />

        <h3 class="text-xl">Instructor Level</h3>
        <small
            >Pick the level(s) that best describes you as an instructor.</small
        >

        <EditInstructorLevelForm
            :instructor-levels="instructorLevels"
            :myInstructorLevels="myInstructorLevels"
            :redirect="route('registration.add-subject-view')"
        />
        <BackButton />
    </JetAuthenticationCard>
</template>
