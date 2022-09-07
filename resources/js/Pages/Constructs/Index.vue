<template>
    <app-layout title="Constructs">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                 <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h2 class="text-xl font-medium leading-6 text-gray-900">Constructs</h2>
                    </div>
                    <div v-if="constructs.length != 0" class="ml-4 mt-2 flex-shrink-0">
                        <a href="constructs/create"><button type="button" class="relative inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">New Construct</button></a>
                    </div>
                 </div>
                </div>

                <div class="overflow-hidden">
                    <div v-if="constructs.length == 0" class="text-center  pt-10 mt-10 ">
                        <div>
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"  xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>

                            <h3 class="mt-2 text-sm font-medium text-gray-900">No constructs</h3>
                            <p class="mt-1 text-sm text-gray-500">Create a new construct.</p>  
                            <div class="mt-6">
                                <a href="questionnaires/create"><button type="button" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <!-- Heroicon name: mini/plus -->
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                    </svg>
                                    New Construct
                                </button></a>
                            </div>
                            </div>
                        </div>
                    <table v-if="constructs.length != 0"  class="min-w-full divide-y divide-gray-300 shadow rounded-md border pt-10 mt-10 ">
                        <thead class="bg-gray-50">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Construct</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Statements</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                            <th></th>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(construct, index) in constructs" class="border m-1 border-gray-200">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-sm text-gray-900 sm:pl-6">{{ index + 1 }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900">
                                    <div class="font-bold" :title="construct.objective">{{ construct.name }}</div>
                                </td>
                                <td class="whitespace-normal px-3 py-4 text-sm text-gray-500">
                                    <div v-for="statement in construct.statements">
                                        {{ statement.text }}
                                    </div>
                                </td>
                                <td class="uppercase text-sm text-gray-500">
                                    <div class="flex flex-row align-middle">
                                    <a  :href="'/constructs/'+construct.id+'/edit'" class="mx-2" >  
                                        <el-tooltip content="Edit" placement="top">
                                            <el-button class=" hover:text-indigo-900 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-indigo-600 w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </el-button>
                                        </el-tooltip>
                                    </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import NavLink from '@/Jetstream/NavLink'
import JetButton from '@/Jetstream/Button.vue'

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
    },
    props: ['constructs'],
})
</script>
