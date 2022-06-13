<template>

    <div class="inline-block">

        <div v-on:click="showResultsDialog" class="hidden sm:block underline cursor-pointer">{{ construct.name }}</div>
        <div class="sm:hidden">{{ construct.name }}</div>

        <!-- Feedback Modal -->
        <jet-dialog-modal :show="viewResults" @close="viewResults = false">
            <template #title>
                <div>{{  this.construct.name }}</div>
            </template>

            <template #content>

                <construct-bar-chart :questionnaire_id="questionnaire.id" :construct_id="construct.id" />

            </template>

        </jet-dialog-modal>

    </div>

</template>

<script>
import { defineComponent } from 'vue'
import JetActionSection from '@/Jetstream/ActionSection.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import StatementHeader from "@/Pages/Results/StatementHeader";
import ConstructBarChart from "@/Pages/Results/ConstructBarChart";

export default defineComponent({
    components: {
        JetActionSection,
        JetDialogModal,
        StatementHeader,
        ConstructBarChart,
    },

    props: ['questionnaire', 'construct'],

    data() {
        return {
            viewResults: false,
        }
    },

    methods: {
        showResultsDialog() {
            this.viewResults= true;
        },
        closeModal() {
            this.confirmingUserDeletion = false

            this.form.reset()
        },
    },
})
</script>
