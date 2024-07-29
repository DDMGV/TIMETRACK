<template>
    <div class="container">
        <ul class="mt-5 nav justify-content-center align-items-between">
            <template v-if="$page.props.auth.user">
                <li class="nav-item">
                    <Link :href="route('home')" class="btn">
                    Home
                    </Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('projects.index')" class="btn">
                    Projects
                    </Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('tasks.index')" class="btn">
                    Tasks
                    </Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('profile.edit')" class="btn"> Profile </Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('logout')" method="post" as="button" class="btn">
                        Log Out
                    </Link>
                </li>
            </template>
            <template v-else>
                    <li class="nav-item">
                        <Link :href="route('login')" class="btn">
                        Log-in
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link :href="route('register')" class="btn">
                        Register
                        </Link>
                    </li>
            </template>
        </ul>


        <!-- Conditional rendering based on authentication state -->



        <slot />
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

export default {
    components: {
        Link,
        GuestLayout,
        Dropdown,
        DropdownLink
    }
};
</script>
