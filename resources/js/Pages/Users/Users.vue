<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DeleteDataUser from './Partials/DeleteDataUser.vue';
import dayjs from 'dayjs';


const { props } = usePage();
const users = ref(props.users);

const handleUserDeleted = (id) => {
    users.value = users.value.filter(user => user.id !== id);
};

const formatDate = (date) => {
    return dayjs(date).format('DD MMMM YYYY, HH:mm');
}
</script>

<template>
    <notifications />

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Users</h2>
        </template>

        <div class="py-12">
            <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- create -->
                        <div class="flex justify-between items-center ">
                            <PrimaryButton class="mb-5">
                                <Link :href="route('user.create')">
                                Create
                                </Link>
                            </PrimaryButton>
                        </div>
                        <!-- show all users -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Provinsi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kab/Kota
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kecamatan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Created At
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                <tr v-for="user, index in users" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ index + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ user.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ user.email }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-wrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ user.provinsi }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-wrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ user.kabupaten }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ user.kecamatan }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ formatDate(user.created_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            <!-- action button -->
                                            <div class="flex justify-center">
                                                <SecondaryButton class="me-2">
                                                    <Link :href="route('user.edit', user.id)">
                                                    Edit
                                                    </Link>
                                                </SecondaryButton>
                                                <DeleteDataUser :user="user" @deleted="handleUserDeleted" />
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
