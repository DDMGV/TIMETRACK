<template>
    <transition name="fade" mode="out-in">
        <div v-if="isLoading" class="loader">
            <h1 ref="loaderTitle" class="loader-title">ZEMS.TIMETRACK</h1>
        </div>

        <div v-else>
            <Head :title="title" />

            <Layout></Layout>
            <div class="container">
                <div class="row mt-5 d-flex justify-content-center align-items-center">
                    <div class="col-md-6 col-lg-4">
                        <GuestLayout class="shadow-lg p-4 rounded bg-white">
                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                {{ status }}
                            </div>
                            <h3 class="text-center">Авторизация</h3>
                            <form @submit.prevent="submit">
                                <div class="mb-3">
                                    <InputLabel for="email" value="E-mail" />
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full form-control"
                                        v-model="form.email"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                    <InputError class="mt-2 text-danger" :message="form.errors.email" />
                                </div>

                                <div class="mb-3">
                                    <InputLabel for="password" value="Пароль" />
                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full form-control"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <InputError class="mt-2 text-danger" :message="form.errors.password" />
                                </div>

                                <div class="form-check mb-3">
                                    <label class="form-check-label">
                                        <Checkbox name="remember" v-model:checked="form.remember" class="form-check-input" />
                                        <span class="ms-2 text-sm text-gray-600">Запомнить меня</span>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        Забыли свой пароль?
                                    </Link>
                                    <PrimaryButton class="ms-4 btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Войти
                                    </PrimaryButton>
                                </div>
                            </form>
                        </GuestLayout>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    title: String,
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const isLoading = ref(true);

const animateTitle = () => {
    const title = document.querySelector('.loader-title');
    const letters = title.innerText.split('');
    title.innerHTML = '';

    letters.forEach((letter) => {
        const span = document.createElement('span');
        span.innerText = letter;
        span.style.opacity = 0;
        span.style.display = 'inline-block';
        span.style.transition = `opacity 0.5s ease ${Math.random() * 2}s`;
        title.appendChild(span);

        setTimeout(() => {
            span.style.opacity = 1;
        }, 0);
    });
};

onMounted(() => {
    animateTitle();
    setTimeout(() => {
        isLoading.value = false;
    }, 8000);
});
</script>

<style>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}

.loader-title span {
    opacity: 0;
}
</style>
