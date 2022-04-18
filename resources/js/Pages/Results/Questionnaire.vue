<template>
    <app-layout title="Results">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ questionnaire.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        {{questionnaire.subject}}
                        {{questionnaire.start_time}}
                        {{questionnaire.end_time}}

                    <table>
                        <div v-for="construct in constructs">
                            <tr>
                            <th>{{ construct.name }}</th>
                                <th v-for="(statement, index) in construct.statements">
                                    <div :title="statement.text">{{index + 1}}</div>
                                </th>
                            </tr>
                            <tr v-for="respondent in respondents">
                                <td>-</td>
                                <td v-for="(response, index) in respondent.responses">
                                    {{ response.answer }}
                                </td>
                            </tr>
                        </div>

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

    },
})
</script>
