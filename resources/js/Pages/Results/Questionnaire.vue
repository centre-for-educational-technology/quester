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
                    {{questionnaire.subject}}
                    {{questionnaire.start_time}}
                    {{questionnaire.end_time}}

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border mb-5"
                        v-for="construct in constructs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ construct.name }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    v-for="(statement, index) in construct.statements">
                                    <div :title="statement.text">{{index + 1}}</div>
                                </th>
                            </tr>
                        </thead>
                        <tr v-for="respondent in respondents">
                            <td>
                                <div v-if="respondent.user">{{ respondent.user.name }}</div>
                                <div v-else>-</div>
                            </td>
                            <td v-for="statement in construct.statements">
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

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
    },
    props: ['questionnaire', 'constructs', 'respondents'],
    methods: {
        getRespondentStatementAnswer: function(respondent_responses, statement_id) {
            let answer = "";
            _.forEach(respondent_responses, function(response) {
                if (response.statement_id === statement_id) {
                    answer = response.answer;
                }
            });
            return answer;
        }
    },
})
</script>
