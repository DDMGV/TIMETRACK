<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Удаление аккаунта</h2>

            <p class="mt-1 text-sm text-gray-600">
                Если вы решили изменить свою жизнь, взвесили все риски, убедили родных и близких - удаляйте аккаунт.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="btn btn-danger">Это не больно.</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">


            <h2>
                Cорян, я не хочу тратить время на оформление этого модального окна. Я не думаю что вы сюда будете
                заходить,но если вы зашли, то: Вы уверены что хотите удалить свой аккаунт?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Былое не вернуть. Удалив аккаунт вы навсегда потеряете всю связанную с ним информацию. Но
                возможно softDelete позволит админам восстановить часть информации.
            </p>

            <div class="mt-6">
                <InputLabel for="password" value="Password" class="sr-only" />

                <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                    class="mt-1 block w-3/4" placeholder="Password" @keyup.enter="deleteUser" />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteUser">
                    Delete Account
                </DangerButton>
            </div>

        </Modal>
    </section>
</template>
