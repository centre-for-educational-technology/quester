<template>
    <app-layout title="Constructs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Questionnaires
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <nav-link href="questionnaires/create">Add new Questionnaire</nav-link>
                    <div v-for="questionnaire in questionnaires" class="border m-1 border-gray-200">
                        <b>{{ questionnaire.name }}</b>
                        {{questionnaire.subject}}
                        {{questionnaire.start_time}}
                        {{questionnaire.end_time}}
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

export default defineComponent({
    components: {
        AppLayout,
        NavLink,
        JetButton,
    },
    props: ['questionnaires'],
})
</script>
