<template>

    <div class="inline-block">

        <div v-if="loaded">
            <div v-on:click="showResultsDialog" class="hidden sm:block underline cursor-pointer">{{ construct.name }} (M={{ this.average }})</div>
            <div class="sm:hidden">{{ construct.name }}</div>
        </div>

        <!-- Feedback Modal -->
        <jet-dialog-modal :show="viewResults" @close="viewResults = false">
            <template #title>
                <div>{{  this.construct.name }}</div>
            </template>

            <template #content>

                <questionnaire-statements-bar-chart :questionnaire_id="questionnaire.id" :construct_id="construct.id" />

            </template>

        </jet-dialog-modal>

    </div>

</template>

<script>
import { defineComponent } from 'vue'
import JetActionSection from '@/Jetstream/ActionSection.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import StatementHeader from "@/Pages/Results/StatementHeader";
import QuestionnaireStatementsBarChart from "@/Pages/Results/QuestionnaireStatementsBarChart";

export default defineComponent({
    components: {
        JetActionSection,
        JetDialogModal,
        StatementHeader,
        QuestionnaireStatementsBarChart,
    },

    props: ['questionnaire', 'construct'],

    data() {
        return {
            loaded: false,
            average: '',
            viewResults: false,
        }
    },

    methods: {
        showResultsDialog() {
            this.viewResults= true;
        },
    },
    async created () {

        this.loaded = false

        let params = {
            "questionnaire_id": this.questionnaire.id,
            "construct_id": this.construct.id,
        }

        try {

            await axios.get('/getQuestionnaireConstructAverageResult', {params}).then(response => {
                this.average = response.data;
                this.loaded = true;
            })

        } catch (e) {
            console.error(e)
        }

    }
})
</script>
