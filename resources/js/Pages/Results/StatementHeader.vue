<template>
    <div v-if="loaded">

        ( {{ this.average }}, N={{ this.respondents_count }} )

    </div>
</template>


<script>

export default {

    props: ['questionnaire_id', 'statement_id'],

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
            "statement_id": this.statement_id,
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
