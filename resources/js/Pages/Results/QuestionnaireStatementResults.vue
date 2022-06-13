<template>

    <div class="inline-block w-full">

        <statement-header :index="index" :questionnaire_id="questionnaire.id" :statement="statement"
                          v-on:click="showResultsDialog" class="underline cursor-pointer" />

        <!-- Feedback Modal -->
        <jet-dialog-modal :show="viewResults" @close="viewResults = false">
            <template #title>
                {{  this.statement.text }}
            </template>

            <template #content>

                <statement-bar-chart :questionnaire_id="questionnaire.id" :statement_id="statement.id" />

            </template>

        </jet-dialog-modal>

    </div>

</template>

<script>
import { defineComponent } from 'vue'
import JetActionSection from '@/Jetstream/ActionSection.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetDangerButton from '@/Jetstream/DangerButton.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import StatementHeader from "@/Pages/Results/StatementHeader";
import StatementBarChart from "@/Pages/Results/StatementBarChart";

export default defineComponent({
    components: {
        JetActionSection,
        JetDangerButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
        StatementHeader,
        StatementBarChart,
    },

    props: ['index', 'questionnaire', 'statement'],

    data() {
        return {
            viewResults: false,
        }
    },

    methods: {
        showResultsDialog() {
            this.viewResults= true;
        },
    },
})
</script>
