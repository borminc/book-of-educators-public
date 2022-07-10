<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { watch, ref, onMounted } from "vue";
import axios from "axios";
import Checkbox from "../../../Jetstream/Checkbox.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const props = defineProps({
    subjects: Array,
    submitButtonText: String,
    redirect: String,
});

const form = useForm({
    subject_ids: props.subjects ? props.subjects.map((s) => s.id) : [],
});

const subjects = ref(props.subjects ?? []);
const searchKey = ref("");
watch(searchKey, getSubjects);

async function getSubjects(search = "") {
    const params = search ? { search } : {};
    params.limit = 10;

    const res = await axios.get(route("subjects.index", params));

    subjects.value = subjects.value.filter((s) =>
        form.subject_ids.includes(s.id)
    );
    subjects.value.push(
        ...res.data.filter((s) => !form.subject_ids.includes(s.id))
    );
}

function onSelectSubject(subject) {
    if (form.subject_ids.includes(subject.id)) {
        form.subject_ids = form.subject_ids.filter((id) => id !== subject.id);
    } else {
        form.subject_ids.push(subject.id);
    }
}

async function addSubject(subjectName) {
    try {
        const res = await axios.post(route("subjects.store"), {
            name: subjectName,
        });

        const subject = res.data;

        form.subject_ids.push(subject.id);
        subjects.value = [subject, ...subjects.value];
    } catch (e) {
        console.log(e.response?.data);
        alert("There was a problem.");
    }
}

async function onSubmit(e) {
    const params = {};
    if (props.redirect) params.redirect = props.redirect;
    form.put(route("subject-user.update", params));
}

onMounted(getSubjects);
</script>

<template>
    <form @submit.prevent="onSubmit">
        <JetValidationErrors class="mb-4" />

        <div>
            <JetLabel for="searchKey" value="Search for subjects" />
            <JetInput
                id="searchKey"
                v-model="searchKey"
                type="text"
                class="mt-1 block w-full"
                autofocus
            />
        </div>

        <div>
            <ul class="mt-3">
                <li
                    class="flex items-center"
                    v-if="
                        searchKey &&
                        !subjects.map((s) => s.name).includes(searchKey)
                    "
                >
                    <Checkbox
                        :id="`subject-${searchKey}`"
                        @change="addSubject(searchKey)"
                        :value="searchKey"
                    />
                    <JetLabel
                        :for="`subject-${searchKey}`"
                        :value="searchKey"
                        class="ml-2"
                    />
                </li>

                <li
                    v-for="subject in subjects"
                    :key="subject.id"
                    class="flex items-center my-3 ml-0 mr-12"
                >
                    <Checkbox
                        :id="`subject-${subject.id}`"
                        :checked="form.subject_ids.includes(subject.id)"
                        @change="onSelectSubject(subject)"
                        :value="subject.name"
                    />
                    <JetLabel
                        :for="`subject-${subject.id}`"
                        :value="subject.name"
                        class="ml-2"
                    />
                </li>
            </ul>
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
