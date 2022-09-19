<template>
    
    <PolarArea v-if="loaded"
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
import { PolarArea } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement,RadialLinearScale,Plugin} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, RadialLinearScale)

export default {
    name: 'ContructPolarChart',
    components: { PolarArea },
    props: {
        chartId: {
            type: String,
            default: 'polar-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 300
        },
        height: {
            type: Number,
            default: 300
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
        questionnaire: Number,
        construct: Number,
    },
    data() {
        return {
            loaded: false,
            chartData: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                onClick:this.handle,
                plugins:{
                    legend:{
                        display:false
                    },
                },
            }
        }
    },
    methods:{
        handle (point, event) {
    	    const item = event[0];
            console.log(item);
    	    this.$emit('on-receive', {
                idx: item.index,
      	    
            });
        },
    },
    async created () {
        this.loaded = false
        let params = {
            "questionnaire_id": this.questionnaire.id,
            "construct_id": this.construct.id,
        }
        try {
            console.log(this.questionnaire_id);
            console.log(this.construct_id);
            console.log('Fetching construct');
            await axios.get('/getConstructStatementsAverageResult', {params}).then(response => {
                this.chartData = response.data;
                this.loaded = true;

                console.log(this.chartData);
            })

        } catch (e) {
            console.error(e)
        }
    }
}
</script>
