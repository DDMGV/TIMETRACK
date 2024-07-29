<template>

    <Head :title="title" />

    <Layout>
        <div class="d-flex">
            <h1>{{ title }}</h1>
            <button type="button" class="ms-1 btn btn-outline-dark my-2 border-0" data-bs-toggle="modal"
                data-bs-target="#createModal">
                <i class="bi bi-plus-square"></i> Добавить проект
            </button>
        </div>
    </Layout>
    <div class="container">
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">Добавление проекта</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="was-validated" @submit.prevent="store">
                            <div class="container">
                                <div class="form-floating mb-3">
                                    <input required v-model="form.name" type="text" class="form-control" id="name"
                                        placeholder="Название проекта">
                                    <label for="floatingInput">Название проекта</label>
                                    <div id="name" class="form-text">
                                        К выбору названия нужно подходить ответственно! Попробуйте отразить суть проекта
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
                                    <label for="description">Описание проекта</label>
                                    <div id="description" class="form-text">
                                        Опишите подробности проекта. Например попробуйте донести до коллег суть проекта
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
                                        Определите срок на реализацию проекта. Помните о возможных форс-мажорах и
                                        человеческих факторах. Дата и время начала проекта будет с момента создания.
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

        
        <div v-if="projects.length > 0" class="accordion shadow-lg" id="accordionExample">
            <div class="accordion-item" v-for="(project, index) in projects" :key="project.id">
                <h2 class="accordion-header" :id="'heading' + index">
                    <button class="accordion-button fw-semibold" :class="project.is_done ? 'bg-success bg-opacity-50 text-success' : null " type="button" data-bs-toggle="collapse"
                        :data-bs-target="'#collapse' + index" aria-expanded="true" :aria-controls="'collapse' + index">
                        <h4>{{ project.name }} <span v-if="!project.when_done" class="badge text-bg-primary bg-opacity-50 text-wrap">На выполнение: {{ project.time_difference }}</span></h4> 
                          
                    </button>
                </h2>
                <div :id="'collapse' + index" class="accordion-collapse collapse" :class="{ show: index === 0 }"
                    :aria-labelledby="'heading' + index" data-bs-parent="#accordionExample">
                    <div class="accordion-body" :class="project.is_done ? 'bg-success bg-opacity-10' : null ">

                        <div class="hstack gap-1 d-flex justify-content-center">
                            <div class="p-2"> <span class="badge text-dark border border-dark">{{
                                    project.created_at_formatted }}</span> </div>
                            <div class="p-2"><i class="bi  bi-arrow-right"></i></div>
                            <div class="p-2"><span class="badge  text-dark border border-dark">{{ project.when_must_done
                                    }}</span></div>
                        </div>


                        <div class="row">
                            <h6 class="text-secondary">Описание проекта:</h6>
                            <p>{{ project.description }}</p>
                            <h6 class="text-secondary">Задачи проекта:</h6>
                            <ul class="list-group  list-group-flush">
                                <div v-for="task in project.tasks" :key="task.id"
                                    :class="task.is_done ? 'bg-success bg-opacity-10' : 'bg-white'"
                                    class="list-group-item list-group-item-action rounded" aria-current="true">
                                    <div class="d-flex w-100 justify-content-between">
                                        <a @click="toggleTaskDone(task.id)"
                                            :class="{ 'text-dark': !task.is_done, 'text-success': task.is_done }"
                                            class="text-decoration-none fs-5 mb-1" style="cursor: pointer;">
                                            <i :class="task.is_done ? 'bi bi-check-circle' : 'bi bi-circle'"></i> {{
                                            task.name }}
                                        </a> <small class="text-secondary">Задача добавлена: {{
                                            task.created_at_formatted }}</small>
                                    </div>
                                    <p class="mb-1">{{ task.description }}</p>
                                    <small class="text-secondary">Дедлайн задачи: <i class="bi bi-fire"></i> {{
                                        task.when_must_done }}</small>
                                    <p><small v-if="task.when_done" class="text-success text-end"><strong>Задача
                                                выполнена: <i class="bi bi-check-circle"></i> {{ task.when_done
                                                }}</strong></small></p>
                                </div>
                            </ul>
                        </div>
                        <div class="row mt-2">
                            <hr>
                            <div class="d-flex justify-content-start">
                                <button  @click="toggleProjectDone(project.id)" type="button" class="btn btn-outline-success border-0 "><i
                                        class="bi bi-check-circle"></i>{{ project.is_done ? ' Отменить завершение проекта' : ' Завершить проект' }} </button>
                                <div class="vr mx-1"></div>
                                <button @click="destroy(project.id)" class="btn btn-outline-danger border-0"><i
                                        class="bi bi-trash3"></i> Удалить проект</button>
                                <div class="vr mx-1"></div>
                                <Link :href="route('projects.edit', project.id)"
                                    class="btn btn-outline-warning border-0">
                                <i class="bi bi-pencil-square"></i> Редактировать проект</Link>
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
                <h5 class="card-title">У вас пока нет проектов</h5>
                <p class="card-text">Ну ничего страшного, мы уверены что вы однажды их добавите!</p>
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
    setup(props) {
        const form = useForm({
            name: null,
            author: props.userId,
            description: null,
            is_done: false,
            when_must_done: null,
            when_done: null,
        });

        function store() {
            form.post(route('projects.store'))

        }

        return { form, store };
    },
    props: { // Данные из контроллера
        title: String,
        userId: Number,
        projects: {
            type: Array,
            required: true
        }
    },
    methods: {
        toggleProjectDone(projectId) {
            this.$inertia.patch(this.route('projects.done', projectId));
        },
        toggleTaskDone(taskId) {
            this.$inertia.patch(this.route('tasks.done', taskId));
        },
        destroy(id) {
            if (confirm('Удалить проект?')) {
                this.$inertia.delete(this.route('projects.destroy', id))
            }
        }
    },


};
</script>
