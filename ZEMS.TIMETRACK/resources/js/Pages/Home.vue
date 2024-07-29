<template>
    <Head :title="title" />

    <Layout>
        <h1>{{ title }}</h1>
    </Layout>

    <div class="container">
        <div class="row">
            <div class="col-lg">
                <div class="card h-100 shadow-lg">
                    <div class="card-header">
                        Ваши задачи
                    </div>
                    <div class="card-body">
                        <div v-if="tasks.length > 0" class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col"></th>
                                        <th scope="col">Проект</th>
                                        <th scope="col">Задача</th>
                                        <th scope="col">Время на выполнение задачи</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="task in tasks" :key="task.id" class="text-center">
                                        <td><i :class="task.is_done ? 'bi bi-check-circle text-success' : 'bi bi-circle text-danger'"></i></td>
                                        <td :class="task.is_done ? 'text-success' : 'text-danger'">{{ task.project_name }}</td>
                                        <td :class="task.is_done ? 'text-success' : 'text-danger'">{{ task.name }}</td>
                                        <td :class="task.is_done ? 'text-success' : 'text-danger'">{{ task.time_difference }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <h5 class="card-title">Ой-ой</h5>
                            <p class="card-text">У вас пока нет задач. Вам необходимо их создать!</p>
                            <Link :href="route('tasks.index')" class="btn btn-outline-dark border-0">Хочу создать задачу!</Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col d-flex flex-column">
                <div class="col-lg">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            Ваши активные проекты
                        </div>
                        <div class="card-body">
                            <div v-if="projects.length > 0" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col"></th>
                                            <th scope="col">Проект</th>
                                            <th scope="col">Количество задач</th>
                                            <th scope="col">Время на закрытие проекта</th>
                                            <th scope="col">Проект завершен</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(project, index) in projects" :key="project.id" class="text-center">
                                            <th scope="row"><i :class="'bi bi-' + (index + 1) + '-square'"></i></th>
                                            <td>{{ project.name }}</td>
                                            <td>
                                                Всего: {{ project.tasks.length }}
                                                <span class="badge text-dark text-bg-white border border-1 border-dark">
                                                    <small class="text-success">{{ completedTasksCount(project.tasks) }}</small> +
                                                    <small class="text-danger">{{ uncompletedTasksCount(project.tasks) }}</small>
                                                </span>
                                            </td>
                                            <td>{{ project.time_difference }}</td>
                                            <td :class="project.project_done_time_difference ? 'text-success' : 'text-danger'">{{ project.project_done_time_difference ? project.project_done_time_difference : 'Проект не завершен' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                <h5 class="card-title">Ой-ой</h5>
                                <p class="card-text">У вас пока нет проектов. Попробуйте его создать!</p>
                                <Link :href="route('projects.index')" class="btn btn-outline-dark border-0">Хочу создать проект!</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';


export default {
    components: {
        Head,
        Link,
        Layout,
    },
    props: {
        title: String,
        projects: {
            type: Array,
            required: true
        },
        tasks: {
            type: Array,
            required: true
        },


        
    },
    methods: {
        completedTasksCount(tasks) {
            return tasks.filter(task => task.is_done).length;
        },
        uncompletedTasksCount(tasks) {
            return tasks.filter(task => !task.is_done).length;
        },
    }
};
</script>