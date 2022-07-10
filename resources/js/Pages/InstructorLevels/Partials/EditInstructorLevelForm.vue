<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import Checkbox from "../../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/Checkbox.vue";
import { computed } from "vue";

const props = defineProps({
    instructorLevels: Array,
    myInstructorLevels: Array,
    submitButtonText: String,
    redirect: String,
});

const groupedInstructorLevels = computed(() =>
    props.instructorLevels.reduce((acc, curr) => {
        if (acc[curr.level]) {
            acc[curr.level].push(curr);
        } else {
            acc[curr.level] = [curr];
        }

        return acc;
    }, {})
);

const form = useForm({
    instructor_level_ids: props.myInstructorLevels
        ? props.myInstructorLevels.map((i) => i.id)
        : [],
});

function onSelectLevel(level) {
    if (form.instructor_level_ids.includes(level.id)) {
        form.instructor_level_ids = form.instructor_level_ids.filter(
            (id) => id !== level.id
        );
    } else {
        form.instructor_level_ids.push(level.id);
    }
}

function onSubmit(e) {
    const params = {};
    if (props.redirect) params.redirect = props.redirect;

    if (props.myInstructorLevels) {
        form.put(route("instructor-levels.update", params), {
            preserveScroll: true,
        });
        return;
    }

    form.post(route("registration.add-instructor-level", params), {
        preserveScroll: true,
    });
}
</script>

<template>
    <form class="mt-4" @submit.prevent="onSubmit">
        <JetValidationErrors class="mb-4" />

        <div>
            <div
                :key="group"
                v-for="[group, levels] in Object.entries(
                    groupedInstructorLevels
                )"
                class="mt-4"
            >
                <h3 class="text-lg">{{ group }}</h3>

                <div class="ml-3">
                    <div
                        :key="level.id"
                        v-for="level in levels"
                        class="flex items-center"
                    >
                        <Checkbox
                            :id="`level-${level.id}`"
                            :checked="
                                form.instructor_level_ids.includes(level.id)
                            "
                            @change="onSelectLevel(level)"
                            :value="level.role"
                        />
                        <JetLabel
                            :for="`level-${level.id}`"
                            :value="level.role"
                            class="ml-2"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <JetButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ submitButtonText ?? "Continue" }}
            </JetButton>
        </div>
    </form>
</template>
