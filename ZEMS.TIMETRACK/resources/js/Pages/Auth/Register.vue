<template>

    <Head :title="title" />

    <Layout>

    </Layout>

    <div class="container">
        <div class="row mt-5 d-flex justify-content-center align-items-center">
            <div class="col-md-6 col-lg-4">
                <GuestLayout class="shadow-lg p-4 rounded bg-white">
                    <h3 class="text-center">Регистрация</h3>
                    <form @submit.prevent="submit">
                        <div class="mb-3">
                            <InputLabel for="name" value="Имя" />
                            <TextInput id="name" type="text" class="mt-1 block w-full form-control" v-model="form.name"
                                required autofocus autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mb-3">
                            <InputLabel for="email" value="E-mail" />
                            <TextInput id="email" type="email" class="mt-1 block w-full form-control"
                                v-model="form.email" required autocomplete="username" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mb-3">
                            <InputLabel for="password" value="Пароль" />
                            <TextInput id="password" type="password" class="mt-1 block w-full form-control"
                                v-model="form.password" required autocomplete="new-password" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mb-3">
                            <InputLabel for="password_confirmation" value="Подтвердите пароль" />
                            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full form-control"
                                v-model="form.password_confirmation" required autocomplete="new-password" />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <Link :href="route('login')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Уже зарегистрированы?
                            </Link>
                            <PrimaryButton class="ms-4 btn btn-primary" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Регистрация
                            </PrimaryButton>
                        </div>
                    </form>
                </GuestLayout>
            </div>
        </div>
    </div>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';


const props = defineProps({
    title: String,
    form: Object,
});


const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>