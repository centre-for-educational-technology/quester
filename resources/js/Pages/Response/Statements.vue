<template>

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div v-for="statement in statements" class="p-5">

                <div>{{ statement.text }}</div>

                <div class="flex items-center justify-center space-x-10">
                    <div v-for="n in 5">
                        <div @click="answer(statement, n)" class="cursor-pointer relative flex justify-center items-center text-center p-2 h-10 w-10 rounded-full border border-gray-200 hover:bg-blue-500 hover:border-blue-700"
                             v-bind:class="{ 'bg-blue-500' : statement.answer === n }">
                            {{ n }}
                        </div>
                        <div class="text-sm h-5 text-gray-500">
                            <div v-if="n===1">
                                Not very likely
                            </div>
                            <div v-else-if="n===3">
                                Hard to say
                            </div>
                            <div v-else-if="n===5">
                                Very likely
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10">
                <button @click="submitAnswers()" class="float-right bg-blue-400 hover:bg-blue-500 text-gray-700 mr-2 border rounded px-4 py-2 font-semibold uppercase text-xs">Submit</button>
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
    props: ['questionnaire', 'respondent', 'statements'],
    // iga statemendi kohta peab olema kirjas mis valiti
    data() {
        return {
            responses: [
                { statement_id: '1', 'chosen': '' },
            ],
        }
    },
    methods: {
        answer(statement, answer) {
            statement.answer = answer;
        },
        submitAnswers() {
            console.log('submit responses');
            console.log(this.questionnaire.code);
            console.log(this.statements);
            //route('statement.index');

            //this.form.post(route('statement.index'));
            let form = this.$inertia.form({
                '_method': 'GET',
                questionnaire_code: this.questionnaire.code,
                respondent_id: this.respondent.id,
                statements: this.statements,
            }, {
                bag: 'submitResponse'
            });
            form.post(route('questionnaires.finish'));
        }
    },
})
</script>
