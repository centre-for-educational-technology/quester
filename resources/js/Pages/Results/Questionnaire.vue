<template>
    <app-layout title="Results">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ questionnaire.name }}
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">

                    <div>
                        Subject: {{questionnaire.subject}}
                    </div>
                    <div>
                        Start time: {{ formatDateTime(questionnaire.start_time) }}
                    </div>
                    <div>
                        End time: {{ formatDateTime(questionnaire.end_time) }}
                    </div>

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border my-5"
                        v-for="construct in constructs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">
                                    <questionnaire-construct-results :questionnaire="this.questionnaire" :construct="construct" />
                                </th>
                                <th scope="col" class="text-center font-semibold text-gray-900"
                                    v-for="(statement, index) in construct.statements">
                                    <questionnaire-statement-results :index="index+1" :questionnaire="this.questionnaire" :statement="statement" class="mt-10 sm:mt-0" />
                                </th>
                            </tr>
                        </thead>
                        <tr class="divide-x divide-gray-200" v-for="respondent in respondents">
                            <td>
                                <div v-if="respondent.user">{{ respondent.user.name }}</div>
                                <div v-else>Student</div>
                            </td>
                            <td v-for="statement in construct.statements" class="text-center">
                                <span>{{ getRespondentStatementAnswer(respondent.responses, statement.id) }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border my-5">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">
                                Questionnaire statements
                            </th>
                            <th scope="col" class="text-center font-semibold text-gray-900"
                                v-for="(statement, index) in questionnaire_statements">
                                <questionnaire-statement-results :index="index+1" :questionnaire="this.questionnaire" :statement="statement" class="mt-10 sm:mt-0" />
                            </th>
                        </tr>
                        </thead>
                        <tr class="divide-x divide-gray-200" v-for="respondent in respondents">
                            <td>
                                <div v-if="respondent.user">{{ respondent.user.name }}</div>
                                <div v-else>Student</div>
                            </td>
                            <td v-for="statement in questionnaire_statements" class="text-center">
                                <span>{{ getRespondentStatementAnswer(respondent.responses, statement.id) }}</span>
                            </td>
                        </tr>
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
import QuestionnaireStatementResults from "@/Pages/Results/QuestionnaireStatementResults";
import QuestionnaireConstructResults from "@/Pages/Results/QuestionnaireConstructResults";
import moment from 'moment'

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
        QuestionnaireStatementResults,
        QuestionnaireConstructResults,
    },
    props: ['questionnaire', 'constructs', 'questionnaire_statements', 'respondents'],
    methods: {
        getRespondentStatementAnswer: function(respondent_responses, statement_id) {
            let answer = "";
            _.forEach(respondent_responses, function(response) {
                if (response.statement_id === statement_id) {
                    answer = response.answer;
                }
            });
            return answer;
        },
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        }
    },
})
</script>
