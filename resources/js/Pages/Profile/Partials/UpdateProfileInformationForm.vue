<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import Checkbox from "../../../Jetstream/Checkbox.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

const props = defineProps({
    user: Object,
});

const userRoles = props.user.roles.map((role) => role.name);

const form = useForm({
    _method: "PUT",
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    title: props.user.title,
    bio: props.user.bio,
    photo: null,
    roles: userRoles,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};

function toggleRole(role) {
    if (form.roles.includes(role)) {
        form.roles = form.roles.filter((r) => r !== role);
    } else {
        form.roles.push(role);
    }
}
</script>

<template>
    <JetFormSection @submitted="updateProfileInformation">
        <template #title> Profile Information </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="col-span-6 sm:col-span-4 w-100"
            >
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="my-2">
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="rounded-full h-20 w-20 object-cover"
                    />
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="my-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="
                            'background-image: url(\'' + photoPreview + '\');'
                        "
                    />
                </div>

                <JetSecondaryButton
                    class="mt-2 mr-2"
                    type="button"
                    @click.prevent="selectNewPhoto"
                >
                    Select A New Photo
                </JetSecondaryButton>

                <JetSecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Remove Photo
                </JetSecondaryButton>

                <JetInputError :message="form.errors.photo" class="mt-2" />
            </div>

            <div class="col-span-6">
                <JetValidationErrors class="mb-4" />
            </div>

            <!-- First name -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="first_name" value="First name" />
                <JetInput
                    id="first_name"
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="first_name"
                />
                <JetInputError :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- Last name -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="last_name" value="Last name" />
                <JetInput
                    id="last_name"
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="last_name"
                />
                <JetInputError :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.email" class="mt-2" />

                <div
                    v-if="
                        $page.props.jetstream.hasEmailVerification &&
                        user.email_verified_at === null
                    "
                >
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-gray-600 hover:text-gray-900"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="verificationLinkSent"
                        class="mt-2 font-medium text-sm text-green-600"
                    >
                        A new verification link has been sent to your email
                        address.
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="title" value="Title" />
                <JetInput
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="title"
                />
                <small class="text-gray-500"
                    >This will appear on your profile under your name.</small
                >
                <JetInputError :message="form.errors.title" class="mt-2" />
            </div>

            <!-- Bio -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="bio" value="Bio" />
                <textarea
                    id="bio"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.bio"
                    type="text"
                ></textarea>
                <JetInputError :message="form.errors.bio" class="mt-2" />
            </div>

            <div
                v-if="
                    !(userRoles.length === 1 && userRoles[0] === 'instructor')
                "
                class="col-span-6 sm:col-span-4"
            >
                <JetLabel value="Account type *" />

                <ul class="mt-3">
                    <li class="flex items-center">
                        <Checkbox
                            :id="`role-school`"
                            value="school"
                            :checked="form.roles.includes('school')"
                            disabled
                        />
                        <JetLabel
                            :for="`role-school`"
                            value="School"
                            class="ml-2"
                        />
                    </li>

                    <li class="flex items-center">
                        <Checkbox
                            :id="`role-instructor`"
                            value="instructor"
                            @click="toggleRole('instructor')"
                            :checked="form.roles.includes('instructor')"
                        />
                        <JetLabel
                            :for="`role-instructor`"
                            value="Instructor"
                            class="ml-2"
                        />
                    </li>
                </ul>

                <div class="mt-5">
                    <small class="text-gray-500">
                        * You cannot uncheck school as it is your primary
                        account type. You can only add/remove the instructor
                        account type.
                    </small>
                </div>
            </div>
        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </JetActionMessage>

            <JetButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </JetButton>
        </template>
    </JetFormSection>
</template>
