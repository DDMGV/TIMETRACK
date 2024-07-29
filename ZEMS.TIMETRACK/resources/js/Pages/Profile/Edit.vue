<template>
    <Head :title="title" />

    <Layout>
        <div class="d-flex">
            <h1>{{ title }}</h1>
        </div>
    </Layout>
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="">
                    <div class="">
                        <div class="mt-2">
                            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                                class="max-w-xl" />
                        </div>

                        <div class="mt-2">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>

                        <div class="mt-2">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>
