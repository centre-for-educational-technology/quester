<template>
    <div v-if="loaded" :title="statement.text">

        <div class="px-3 py-3.5 bg-green-400"
            v-if="this.average > 4">
            {{ index }} ( {{ this.average }}, N={{ this.respondents_count }} )
        </div>
        <div class="px-3 py-3.5 bg-yellow-400"
            v-else="this.average >= 3">
            {{ index }} ( {{ this.average }}, N={{ this.respondents_count }} )
        </div>
        <div class="px-3 py-3.5 bg-red-400"
            v-else>
            {{ index }} ( {{ this.average }}, N={{ this.respondents_count }} )
        </div>

    </div>
</template>


<script>

export default {

    props: ['index', 'questionnaire_id', 'statement'],

    data() {
        return {
            loaded: false,
            average: '',
            respondents_count: '',
        }
    },
    async created () {

        this.loaded = false

        let params = {
            "questionnaire_id": this.questionnaire_id,
            "statement_id": this.statement.id,
        }

        try {

            await axios.get('/getQuestionnaireStatementAverageResult', {params}).then(response => {
                this.average = response.data.average;
                this.respondents_count = response.data.respondents_count;
                this.loaded = true;
            })

        } catch (e) {
            console.error(e)
        }

    }
}
</script>
