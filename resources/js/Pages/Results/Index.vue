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
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
                        <th v-if="admin" scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
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
                            <td class="uppercase text-sm text-gray-500"> 
                                    <a :href="'/questionnaires/'+questionnaire.id+'/download'">
                                        <el-tooltip content="Download responses in CSV" placement="top">
                                            <el-button class="bg-gray-300 hover:text-green-800 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                                                <svg class="fill-current w-4 h-4 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                                                <span class="text-blue-500"> Download</span>

                                            </el-button>
                                        </el-tooltip>
                                    </a>
                            </td>
                            <td v-if="admin" class="uppercase text-sm text-gray-500">
                                    <a v-on:click="showAlertConfirm(questionnaire.id)">
                                        <el-tooltip content="Delete" placement="top">
                                            <el-button class=" hover:text-red-800 hover:border-red-800 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6  text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </el-button>
                                        </el-tooltip>
                                    </a>
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
    props: ['questionnaires','admin'],
    data() {
        return {
            base_url: window.location.origin,
            result_id: '',
            questionnaire_code: '',
        }
    },
    methods: {
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        },
        showAlertConfirm(id){
            this.$swal({
              title: 'Are you sure?',
              text: "This will permanently delete the result. You won't be able to revert this!",
              type: 'warning',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#991b1b',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'

            }).then((result) => {
              if (result.value) {
                axios.get('/results/'+id+'/delete')
                    .then(res => {
                        window.location.href = this.base_url + "/results"
                    }).catch(err => {
                    console.log(err)
                })

              }
              else {
                return false;
              }
            });
        }
    },
})
</script>
