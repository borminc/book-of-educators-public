<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import { watch, ref, onMounted, computed } from "vue";
import axios from "axios";
import Checkbox from "../../../Jetstream/Checkbox.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const props = defineProps({
    school: Object,
    submitButtonText: String,
    redirect: String,
});

const form = useForm({
    name: props.school?.name ?? "",
    user_role_in_school: props.school?.user_role_in_school ?? "",
});

const focusedInput = ref(null);

const suggestions = ref([]);

watch(
    [focusedInput, form],
    async ([focusedInput, form]) => {
        if (!focusedInput) {
            suggestions.value = [];
            return;
        }

        try {
            const q =
                focusedInput === "name"
                    ? form.name ?? ""
                    : form.user_role_in_school ?? "";

            const url = route("companies.suggest", {
                property: focusedInput,
                q,
            });

            const res = await axios.get(url);
            suggestions.value = res.data;
        } catch (e) {
            suggestions.value = [];
        }
    },
    { deep: true }
);

function onUnfocusInput() {
    setTimeout(() => {
        focusedInput.value = null;
    }, 200);
}

async function onSubmit(e) {
    const params = {};
    if (props.redirect) params.redirect = props.redirect;

    if (props.school) {
        form.put(route("school-user.update", params));
        return;
    }

    form.post(route("school-user.store", params));
}
</script>

<template>
    <form @submit.prevent="onSubmit">
        <JetValidationErrors class="mb-4" />

        <div class="mt-4">
            <JetLabel for="name" value="School name" />
            <JetInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="name"
                @focus="focusedInput = 'name'"
                @blur="onUnfocusInput"
            />
        </div>
        <div v-if="focusedInput === 'name'">
            <ul class="flex flex-wrap mt-1">
                <li
                    class="my-1 mr-1 py-1 px-2 bg-indigo-400 text-white rounded-md cursor-pointer"
                    v-for="s in suggestions"
                    :key="s"
                    @click="form.name = s"
                >
                    {{ s }}
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <JetLabel for="user_role_in_school" value="Role in school" />
            <JetInput
                id="user_role_in_school"
                v-model="form.user_role_in_school"
                type="text"
                class="mt-1 block w-full"
                autocomplete="user_role_in_school"
                @focus="focusedInput = 'role'"
                @blur="onUnfocusInput"
            />
            <div v-if="focusedInput === 'role'">
                <ul class="flex flex-wrap mt-1">
                    <li
                        class="my-1 mr-1 py-1 px-2 bg-indigo-400 text-white rounded-md cursor-pointer"
                        v-for="s in suggestions"
                        :key="s"
                        @click="form.user_role_in_school = s"
                    >
                        {{ s }}
                    </li>
                </ul>
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
