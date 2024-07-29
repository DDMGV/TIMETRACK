<template>

    <Head :title="title" />

    <Layout>
        <div class="d-flex">
            <h1>{{ title }} {{ project.name }}</h1>
        </div>
    </Layout>
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header">
                somewhere
            </div>
            <div class="card-body">


                <form class="was-validated" @submit.prevent="update">
                    <div class="container">
                        <div class="form-floating mb-3">
                            <input required v-model="form.name" type="text" class="form-control" id="name"
                                placeholder="Название проекта">
                            <label for="name">Название проекта</label>
                            <div class="form-text">
                                К выбору названия нужно подходить ответственно! Попробуйте отразить суть проекта
                                короткой фразой. <br>
                                Если у Вас не получится - поменяете позже.
                            </div>
                            <div class="valid-feedback">Отлично!</div>
                            <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea v-model="form.description" class="form-control" placeholder="Описание проекта"
                                id="description" required></textarea>
                            <label for="description">Описание проекта</label>
                            <div class="form-text">
                                Опишите подробности проекта. Например попробуйте донести до коллег суть проекта
                                или желаемый результат.
                            </div>
                            <div class="valid-feedback">Великолепно!</div>
                            <div class="invalid-feedback" v-if="form.errors.description">{{ form.errors.description }}
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input v-model="form.when_must_done" type="datetime-local" class="form-control"
                                id="when_must_done" required>
                            <label for="when_must_done">Когда дедлайн?</label>
                            <div class="form-text">
                                Определите срок на реализацию проекта. Помните о возможных форс-мажорах и
                                человеческих факторах. Дата и время начала проекта будет с момента создания.
                            </div>
                            <div class="valid-feedback">Восхитительно!</div>
                            <div class="invalid-feedback" v-if="form.errors.when_must_done">{{
                                form.errors.when_must_done }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-dark "><i class="bi bi-check-square"></i>
                            Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

export default {
    components: {
        Layout,
        Head
    },
    setup(props) {
        const form = useForm({
            name: props.project.name,
            description: props.project.description,
            when_must_done: props.project.when_must_done, // Устанавливаем текущую дату в формате для datetime-local
        });

        function update() {
            form.put(route('projects.update', props.project.id));
        }

        return { form, update };
    },
    props: {
        title: String,
        project: {
            type: Object,
            required: true
        }
    }
};
</script>
