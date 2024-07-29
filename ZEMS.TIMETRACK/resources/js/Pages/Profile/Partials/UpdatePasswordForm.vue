<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2>Изменение пароля</h2>

            <p>
                Чтобы обеспечить безопасность, в вашей учетной записи используется длинный случайный пароль.
            </p>
        </header>

        <form @submit.prevent="updatePassword">
            <div>
                <InputLabel for="current_password" value="Текущий пароль" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="form-control"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.current_password" class="mt-2 text-danger" />
            </div>

            <div >
                <InputLabel for="password" value="Новый пароль" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="form-control"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2 text-danger" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Подтвердите новый пароль" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="form-control"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2 text-danger" />
            </div>

            <div >
                <PrimaryButton class="btn btn-success mt-2" :disabled="form.processing">Сохранить изменения</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="alert alert-success mt-2 text-center" role="alert">Сохранено!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
