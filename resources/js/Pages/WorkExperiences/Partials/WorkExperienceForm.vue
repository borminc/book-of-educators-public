<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    workExperience: Object,
});

const form = useForm({
    company: "",
    title: "",
    location: "",
    start_date: "",
    end_date: "",
    description: "",
    ...props.workExperience,
});

async function onSubmit(e) {
    if (props.workExperience) {
        // update
        form.put(
            route("work-experiences.update", {
                work_experience: props.workExperience.id,
            })
        );
        return;
    }

    // add
    form.post(route("work-experiences.store"));
}
</script>

<template>
    <form @submit.prevent="onSubmit" class="m-3">
        <div class="mt-4">
            <JetLabel for="company" value="Company" required />
            <JetInput
                id="company"
                v-model="form.company"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="company"
            />
        </div>

        <div class="mt-4">
            <JetLabel for="title" value="Job title" required />
            <JetInput
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                autofocus
            />
        </div>

        <div class="mt-4">
            <JetLabel for="location" value="Location" />
            <JetInput
                id="location"
                v-model="form.location"
                type="text"
                class="mt-1 block w-full"
                autofocus
                autocomplete="location"
            />
        </div>

        <div class="mt-4">
            <JetLabel for="start_date" value="Start date" required />
            <JetInput
                type="date"
                id="start_date"
                v-model="form.start_date"
                class="mt-1 block w-full"
                autofocus
            />
        </div>

        <div class="mt-4">
            <JetLabel for="end_date" value="End date" />
            <JetInput
                type="date"
                id="end_date"
                v-model="form.end_date"
                class="mt-1 block w-full"
                autofocus
            />
            <small class="text-gray-500"
                >Leave blank if you are still working this position.</small
            >
        </div>

        <div class="mt-4">
            <JetLabel for="description" value="Description" />
            <textarea
                id="description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                v-model="form.description"
                type="text"
                autofocus
            ></textarea>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link
                v-if="props.workExperience"
                method="delete"
                as="button"
                :href="
                    route('work-experiences.destroy', {
                        work_experience: props.workExperience.id,
                    })
                "
                class="underline text-sm text-red-600 hover:text-red-900"
            >
                Delete
            </Link>

            <JetButton
                :class="{ 'opacity-25': form.processing }"
                class="ml-4"
                :disabled="form.processing"
            >
                {{ workExperience ? "Save" : "Add" }}
            </JetButton>
        </div>
    </form>
</template>
