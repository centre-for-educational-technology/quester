<template>

    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div>
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-between w-full md:w-auto">
                                    <a href="#">
                                        <span class="sr-only">Workflow</span>
                                        <img alt="Workflow" class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg">
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">{{ questionnaire.name }}</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg md:mt-5 md:text-xl lg:mx-0">
                            This is the feedback questionnaire shared by your teacher.
                        </p>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg md:mt-5 md:text-xl lg:mx-0">
                            Questionnaire will be active {{ formatDateTime(questionnaire.start_time) }} - {{ formatDateTime(questionnaire.end_time) }}
                        </p>
                        <div class="mt-5">
                            <button @click="startResponse()" class="mt-3 w-1/2 inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Start</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

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
    props: ['questionnaire'],
    data() {
        return {
            questionnaire_code: this.questionnaire.code,
        }
    },
    methods: {
        startResponse() {
            let form = this.$inertia.form({
                '_method': 'GET',
                questionnaire_code: this.questionnaire_code,
            }, {
                bag: 'startResponse'
            });
            form.post(route('questionnaires.statements'));
        },
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        }
    },
})
</script>
