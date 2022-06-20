<template>
    <app-layout title="Results">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Results
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border">
                        <thead class="bg-gray-50">
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Subjects</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Start Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">End Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Responses</th>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(questionnaire, index) in questionnaires" class="border m-1 border-gray-200">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-sm text-gray-900 sm:pl-6">{{ index + 1 }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900">
                                <nav-link :href="'questionnaires/'+questionnaire.id">
                                    <b>{{ questionnaire.name }}</b>
                                </nav-link>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{questionnaire.subject}}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDateTime(questionnaire.start_time) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDateTime(questionnaire.end_time) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{questionnaire.respondents.length}}
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
import moment from "moment";

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
    },
    props: ['questionnaires'],
    methods: {
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        }
    },
})
</script>
