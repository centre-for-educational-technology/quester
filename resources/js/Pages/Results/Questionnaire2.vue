<template>
    <app-layout title="Results">
        <template #header>
            <div class="grid justify-items-center mb-5">
                <h1 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ questionnaire.name }}
                </h1>
            </div> 
        </template>
        <div class="py-2">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex justify-around mb-5">
                    <div class=" mx-3 p-4 flex flex-col">
                        <div class="grid justify-around mb-3"><h3>Overall Score</h3></div><br/>
                        <ve-progress :progress="progress" :size="270" :emptyThickness="30" @click="alert('hello');"  :thickness="32" :legend="score" legend-class="font-bold text-7xl text-indigo-500">
                            <template #legend><span slot="legend"></span></template>
                            <template #legend-caption><p class="text-xs text-gray-500">Total {{respondents.length}} responses</p></template>
                        </ve-progress>
                    </div>
                    <div v-if="requested" id="statement_wise" class="transition-all mx-3 p-4">
                        <div class="flex">
                            
                            <p class="flex-1 text-xl  mb-3">Statement wise scores</p> <button class="flex-none float-right font-extrabold hover:border-1 rounded-md" @click="requested=false">X</button>
                         </div>
                       
                        <div v-for="construct in constructs">
                            <construct-polar-chart :questionnaire="questionnaire" :construct="construct" @on-receive="update"/>
                        </div>
                    </div>
                    <div v-if="requested2" class="m-3">
                         <div class="flex">
                            <p class="flex-1 text-xl  mb-3">Statement wise summary</p> <button class="flex-none float-right font-extrabold hover:border-1 rounded-md" @click="requested2=false">X</button>
                         </div>
                        <span class="text-gray-500 font-medium">Statement:</span><span class="text-dark">{{statement_to_show.text}}</span>
                        <statement-bar-chart :key="statement_to_show.id" :questionnaire_id="questionnaire.id" :statement_id="statement_to_show.id" :height="200" cssClasses="rounded-md bg-white p-4"/>
                    </div>
                </div>
                <div class="flex justify-center mt-5">
                    <button class=" mt-5 rounded-lg w-1/4 p-3 text-xl bg-indigo-500 border-2 border-indigo-700 text-white" @click="requested=true"> Explore more</button>
                </div>
                <div class="flex justify-center mt-5">
                    <div class="rounded-lg p-4 flex flex-row bg-amber-100 text-amber-900">
                        <div class=" flex-none p-4 text-amber-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                            </svg>
                        </div>
                        <div class="p-4 grow">
                            <p class="text-xl" v-html="feedback"></p>
                            
                        </div>
                    </div>
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
import QuestionnaireStatementsResults from "@/Pages/Results/QuestionnaireStatementsResults";
import ConstructPolarChart from "@/Pages/Results/ConstructPolarChart";
import StatementBarChart from "@/Pages/Results/StatementBarChart";
import moment from 'moment'





export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
        QuestionnaireStatementResults,
        QuestionnaireConstructResults,
        QuestionnaireStatementsResults,
        ConstructPolarChart,
        StatementBarChart,
    },
    data(){
        return {
            'total_statements':this.questionnaire_statements.length,
            'statement_to_show':null,
            'requested':false,
            'requested2':false,
            'score':this.score,
            'progress': (100 * this.score/5).toFixed(2)
        }
    },
    props: ['questionnaire', 'constructs', 'questionnaire_statements', 'respondents','feedback','score'],
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
        update(evt){
            this.statement_to_show = this.constructs[0].statements[evt.idx];
            this.requested2 = true;
            window.scrollTo(0, document.body.scrollHeight);
        },
        formatDateTime(datetime)  {
            return moment.utc(datetime).local().format("DD.MM.YYYY H:mm");
        }
    },
})
</script>
