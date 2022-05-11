<template>
    <app-layout title="Constructs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Questionnaires
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <nav-link href="questionnaires/create">Add new Questionnaire</nav-link>
                    <div v-for="questionnaire in questionnaires" class="border m-1 border-gray-200">
                        <b>{{ questionnaire.name }}</b>
                        {{questionnaire.subject}}
                        {{questionnaire.start_time}}
                        {{questionnaire.end_time}}
                        <span class=cursor-pointer @click="showLinkDialog(questionnaire.code)">Link</span>
                    </div>

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

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
        QRCodeVue3,
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
        }
    },
})
</script>
