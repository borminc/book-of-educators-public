<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import Checkbox from "../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/Checkbox.vue";
import route from "../../../../vendor/tightenco/ziggy/src/js";

const props = defineProps({
    roles: Array,
});

const form = useForm({
    roles: props.roles.map((r) => r.name),
});

function onSelectRole(role) {
    form.roles = [role];
    // if (form.roles.includes(role)) {
    //     form.roles = form.roles.filter((r) => r !== role);
    // } else {
    //     form.roles.push(role);
    // }
}

function onSubmit(e) {
    const params = {
        redirect: form.roles.includes("school")
            ? route("registration.add-school-view")
            : route("registration.add-instructor-level-view"),
    };

    form.post(route("registration.select-roles", params), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Register" />

    <JetAuthenticationCard>
        <template #logo>
            <JetAuthenticationCardLogo />
        </template>

        <JetValidationErrors class="mb-4" />

        <h3 class="text-xl">What is your role?</h3>
        <small>Pick the role that best describes you.</small>

        <form class="mt-4" @submit.prevent="onSubmit">
            <div class="flex items-center mt-4">
                <Checkbox
                    type="radio"
                    id="instructor"
                    :checked="form.roles.includes('instructor')"
                    @change="onSelectRole('instructor')"
                    value="instructor"
                />
                <JetLabel
                    for="instructor"
                    value="Instructor"
                    class="ml-2 text-lg"
                />
            </div>

            <p class="text-sm">
                An instructor account enables you to input your information
                related to your career in education, which makes you
                discoverable to potential employers. Choose this if you are
                looking for work as an instructor.
            </p>

            <div class="flex items-center mt-4">
                <Checkbox
                    type="radio"
                    id="school"
                    :checked="form.roles.includes('school')"
                    @change="onSelectRole('school')"
                    value="school"
                />
                <JetLabel for="school" value="School" class="ml-2 text-lg" />
            </div>

            <p class="text-sm">
                A school account enables you to search and filter instructors
                based on the collected information from all registered
                instructors. Choose this if you are looking to hire instructors.
            </p>

            <div class="flex items-center justify-end mt-4">
                <JetButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Continue
                </JetButton>
            </div>
        </form>
    </JetAuthenticationCard>
</template>
