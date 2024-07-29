<template>

    <Head :title="title" />

    <Layout>
        <div class="d-flex">
            <h1>{{ title }}</h1>
            <button type="button" class="ms-1 btn btn-outline-dark my-2 border-0" data-bs-toggle="modal"
                data-bs-target="#createModal">
                <i class="bi bi-plus-square"></i> Добавить задачу
            </button>
        </div>
    </Layout>
    <div class="container">
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">Постановка задачи</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="was-validated" @submit.prevent="store">
                            <div class="container">

                                <select v-model="form.project_id" class="form-select" id="project_id"
                                    aria-label="Default select example">
                                    <option value="" disabled selected>Выберите проект</option>
                                    <option v-for="(project, index) in projects" :key="project.id" :value="project.id">
                                        {{ project.name }}
                                    </option>
                                </select>

                                <div id="project_id" class="form-text">
                                    Выберите проект, в который хотите добавить задачу
                                </div>
                                <div class="valid-feedback">
                                    Отлично!
                                </div>
                                <div class="invalid-feedback" v-if="form.errors.project_id">{{ form.errors.project_id }}
                                </div>

                                <div class="form-floating my-3">
                                    <input required v-model="form.name" type="text" class="form-control" id="name"
                                        placeholder="Название проекта">
                                    <label for="floatingInput">Название задачи</label>
                                    <div id="name" class="form-text">
                                        К выбору названия нужно подходить ответственно! Попробуйте отразить суть задачи
                                        короткой фразой. <br>
                                        Если у Вас не получится - поменяете позже.
                                    </div>
                                    <div class="valid-feedback">
                                        Отлично!
                                    </div>
                                    <div class="invalid-feedback" v-if="form.errors.name">{{ form.errors.name }}</div>


                                </div>


                                <div class="form-floating mb-3">
                                    <textarea v-model="form.description" class="form-control"
                                        placeholder="Leave a comment here" id="description" required></textarea>
                                    <label for="description">Описание задачи</label>
                                    <div id="description" class="form-text">
                                        Опишите подробности задачи. Например попробуйте донести до коллег суть задачи
                                        или
                                        желаемый
                                        результат.
                                    </div>
                                    <div class="valid-feedback">
                                        Великолепно!
                                    </div>
                                    <div class="invalid-feedback" v-if="form.errors.description">{{
                                        form.errors.description
                                    }}</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input v-model="form.when_must_done" type="datetime-local" class="form-control"
                                        id="when_must_done" placeholder="Название проекта" required>
                                    <label for="when_must_done">Когда дедлайн?</label>
                                    <div id="when_must_done" class="form-text">
                                        Определите срок на выполнение задачи. Помните о возможных форс-мажорах и
                                        человеческих факторах.
                                    </div>
                                    <div class="valid-feedback">
                                        Восхитительно!
                                    </div>
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
        </div>
        <div class="card shadow-lg" v-if="tasks.length > 0" >
            <div class="card-body">
                <div class="list-group">
                    <div v-for="(task) in tasks" :key="task.id" href="#" class="list-group-item" :class="task.is_done ? 'bg-success bg-opacity-10' : 'bg-white'" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <a @click="toggleDone(task.id)" :class="{'text-dark': !task.is_done, 'text-success': task.is_done}" class="text-decoration-none mb-1 d-flex" style="cursor: pointer;">
                               <i :class="task.is_done ? 'bi bi-check-circle' : 'bi bi-circle'" class="me-1"></i> <h5> {{ task.name }} <span v-if="!task.when_done" class="badge bg-dark text-wrap"> На выполнение: {{task.time_task_difference}}</span></h5>
                            </a>
                            <small class="text-secondary">Задача добавлена: {{ task.created_at_formatted }}</small>
                        </div>
                        <p class="mb-1"><p>{{ task.description }}</p></p>
                        <div class="row">
                            <div class="col d-flex flex-column">
                                <small class="text-secondary">Дедлайн задачи: <i class="bi bi-fire"></i> {{ task.when_must_done }}</small>
                                <small v-if="task.when_done" class="text-success"><strong>Задача выполнена:  <i class="bi bi-check-circle"></i> {{ task.when_done }}</strong></small>
                            </div>
                            <div class="col text-end">
                                <Link :href="route('tasks.edit', task.id)" class="text-end fs-3 text-warning"><i class="bi bi-pencil-square"></i></Link>
                                <div class="vr mx-2"></div>
                                <a  @click="destroy(task.id)" class="text-end fs-3 text-danger"><i class="bi bi-trash3"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="card shadow-lg">
            <div class="card-header">
                Ой-ой
            </div>
            <div class="card-body">
                <h5 class="card-title">У вас пока нет задач</h5>
                <p class="card-text">Ну и слава богу! Сидите на жопе ровно и кайфуйте.</p>
            </div>
        </div>
    </div>

</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: {
        Link,
        Layout,
        Head,
    },

    props: { // Данные из контроллера
        title: String,
        userId: Number,
        tasks: {
            type: Array,
            required: true
        },
        projects: {
            type: Array,
            required: true
        }
    },
    
    setup(props) {
        const form = useForm({
            name: null,
            author: props.userId,
            project_id: null,
            description: null,
            when_must_done: null,
        });

        function store() {
            form.post(route('tasks.store'))

        }

        return { form, store };
    },
   
    methods: {
        toggleDone(taskId) {
            this.$inertia.patch(this.route('tasks.done', taskId));
        },
        destroy(id) {
            if (confirm('Удалить задачу?')) {
                this.$inertia.delete(this.route('tasks.destroy', id))
            }
        },

       
    },


};
</script>
