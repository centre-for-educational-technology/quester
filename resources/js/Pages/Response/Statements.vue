<template>

    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-2 bg-white sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">

                </svg>

                <div>
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-left w-full md:w-auto">
                                    <a href="#">
                                        <span class="sr-only">Workflow</span>
                                        <img alt="Workflow" class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg">
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>


                </div>

                <main class=" border-1 rounded-lg drop-shadow-2xl bg-white mt-2 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="h-full text-center lg:text-left">
                        <h1 class=" text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl mb-15">
                            <span>{{ questionnaire.name }}</span>
                        </h1>
                        <div class="m-5"></div>
                        <hr/><br/>


                        <div v-for="statement in statements" class=" pt-5 text-sm lg:text-base ">
                            <div class="text-xs mt-3 mb-3">{{ statement.text }} </div>
                            <div class="space-x-10 w-full flex justify-around p-3">
                                <div class="flex flex-col justify-center items-center text-center" v-for="n in 5">
                                    <div @click="answer(statement, n)" class="cursor-pointer relative justify-center items-center text-center pt-1  h-9 w-9 rounded-full border border-gray-200 hover:text-white hover:bg-blue-500 hover:border-blue-700"
                                    v-bind:class="{ 'bg-blue-500 text-white font-bold' : statement.answer === n }">
                                        {{ n }}
                                    </div>
                                    <div class="h-20 lg:h-10  pt-3 text-gray-500 flex">
                                        <div v-if="n===1">
                                        Not very likely
                                        </div>
                                        <div  v-if="n===2">
                                        Not likely
                                        </div>
                                        <div v-else-if="n===3">
                                        Hard to say
                                        </div>
                                        <div v-if="n===4">
                                        Likely
                                        </div>
                                        <div v-else-if="n===5">
                                        Very likely
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-12 flex flex-row-reverse">
                            <button @click="submitAnswers()" class=" bg-blue-500 hover:bg-blue-700 text-white mr-2 mb-4 border  rounded-lg px-4 py-2 font-semibold uppercase w-full sm:w-32 text-sm">Submit</button>
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

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
    },
    props: ['questionnaire', 'statements', 'start_time'],
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
            let allAnswered = this.checkIfAllAnswered(this.statements);
            if (!allAnswered) {
                this.$swal('Not answered', 'Please answer all questions.', 'info');
            } else {
                let form = this.$inertia.form({
                    '_method': 'GET',
                    questionnaire_code: this.questionnaire.code,
                    statements: this.statements,
                    start_time: this.start_time,
                }, {
                    bag: 'submitResponse'
                });
                form.post(route('questionnaires.finish'));
            }
        },
        checkIfAllAnswered(statements) {
            let is_answered = true;
            _.forEach(statements, function(statement) {
                //check if exists statement answer
                let does_exist = _.has(statement, 'answer');
                if (does_exist === false) {
                    is_answered = false;
                }
            });
            return is_answered;
        },
    },
})
</script>
