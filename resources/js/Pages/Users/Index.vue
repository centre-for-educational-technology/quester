<template>
    <app-layout title="Users">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border">
                        <thead class="bg-gray-50">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                            <th></th>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(user, index) in users.data" class="border m-1 border-gray-200">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-sm text-gray-900 sm:pl-6">{{ index + 1 }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900 font-semibold">{{ user.name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900">{{ user.email }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="inline-block" v-for="(role, index) in user.roles">
                                        <span class="mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ role.name }}</span>
                                </div>
                            </td>
                            <td class="uppercase text-sm">
                                <a :href="'/users/'+user.id+'/edit'">
                                        <el-tooltip content="Edit" placement="top">
                                            <el-button class=" hover:text-indigo-900 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-indigo-600 w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </el-button>
                                        </el-tooltip>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <pagination class="mt-10" :links="users.links" />

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from "@/Components/Pagination.vue";

export default defineComponent({
    components: {
        AppLayout,
        Pagination,
    },
    props: ['users'],
})
</script>
