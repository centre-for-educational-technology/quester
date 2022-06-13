<template>
    <Bar v-if="loaded"
        :chart-options="chartOptions"
        :chart-data="chartData"
        :chart-id="chartId"
        :dataset-id-key="datasetIdKey"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
    />
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    name: 'StatementBarChart',
    components: { Bar },
    props: {
        chartId: {
            type: String,
            default: 'bar-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Object,
            default: () => {}
        },
        questionnaire_id: Number,
        statement_id: Number,
    },
    data() {
        return {
            loaded: false,
            chartData: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: true,
            }
        }
    },
    async created () {

        this.loaded = false

        let params = {
            "questionnaire_id": this.questionnaire_id,
            "statement_id": this.statement_id,
        }

        try {

            await axios.get('/getStatementData', {params}).then(response => {
                this.chartData = response.data;
                this.loaded = true;
            })

        } catch (e) {
            console.error(e)
        }

    }
}
</script>
