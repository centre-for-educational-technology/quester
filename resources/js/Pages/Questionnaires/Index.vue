<template>
    <app-layout title="Constructs">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Questionnaires
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <nav-link href="questionnaires/create">Add new Questionnaire</nav-link>

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border">
                        <thead class="bg-gray-50">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Subjects</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Start Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">End Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Constructs</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Share</th>
                            <th></th>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(questionnaire, index) in questionnaires.data" class="border m-1 border-gray-200">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-sm text-gray-900 sm:pl-6">{{ index + 1 }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900"><b>{{ questionnaire.name }}</b></td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{questionnaire.subject}}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDateTime(questionnaire.start_time) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ formatDateTime(questionnaire.end_time) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="inline-block" v-for="(construct, index) in questionnaire.constructs">
                                        <span :title="construct.name"
                                             class="mr-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ index + 1 }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="cursor-pointer underline" @click="showLinkDialog(questionnaire.code)">Link</span>
                                </td>
                                <td class="uppercase text-sm text-gray-500">
                                    <a :href="'/questionnaires/'+questionnaire.id+'/edit'">Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination class="mt-10" :links="questionnaires.links" @pagination-change-page="getQuestionnaires"/>

                    <el-dialog v-model="dialogVisible">
                        <div class="text-center">
                            <QRCodeVue3 class="inline-block"
                                :value="this.questionnaire_url"
                            />
                            <div class="mt-5 text-2xl">
                                Or use code:
                                <span class="font-bold">{{ this.questionnaire_code }}</span>
                            </div>
                        </div>
                    </el-dialog>

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
import QRCodeVue3 from 'qrcode-vue3'
import Pagination from "@/Components/Pagination.vue";
import moment from "moment";

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
        QRCodeVue3,
        Pagination,
    },
    props: ['questionnaires'],
    data() {
        return {
            dialogVisible: false,
            base_url: window.location.origin,
            questionnaire_url: '',
            questionnaire_code: '',
        }
    },
    methods: {
        showLinkDialog(questionnaire_code) {
            this.questionnaire_code = questionnaire_code;
            this.questionnaire_url = this.base_url + '/start?questionnaire_code=' + questionnaire_code;
            this.dialogVisible = true;
        },
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        }
    },
})
</script>
