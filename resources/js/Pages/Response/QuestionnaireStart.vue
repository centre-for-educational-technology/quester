<template>

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <h1>{{ questionnaire.name }}</h1>
            <div>
                This is the feedback questionnaire shared by your teacher.
            </div>
            <div>
                Questionnaire will be active {{ questionnaire.start_time }} - {{ questionnaire.end_time }}
            </div>
            {{ questionnaire.code }}

            <div class="mt-2">
                <button @click="startResponse()" class="bg-blue-400 hover:bg-blue-500 text-gray-700 mr-2 border rounded px-4 py-2 font-semibold uppercase text-xs">Start</button>
            </div>

        </div>
    </div>

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
    props: ['questionnaire'],
    data() {
        return {
            questionnaire_code: this.questionnaire.code,
        }
    },
    methods: {
        startResponse() {
            console.log('start responding');
            console.log(this.questionnaire_code);
            //route('statement.index');

            //this.form.post(route('statement.index'));
            let form = this.$inertia.form({
                '_method': 'GET',
                questionnaire_code: this.questionnaire_code,
            }, {
                bag: 'startResponse'
            });
            form.post(route('questionnaires.statements'));
        },
    },
})
</script>
