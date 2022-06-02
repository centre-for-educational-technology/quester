<template>
    <app-layout title="Constructs">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Constructs and Statements
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden">

                    <nav-link href="constructs/create">Add new Construct</nav-link>

                    <table class="min-w-full divide-y divide-gray-300 shadow rounded-md border">
                        <thead class="bg-gray-50">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">#</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Construct</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Statements</th>
                            <th></th>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(construct, index) in constructs" class="border m-1 border-gray-200">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-sm text-gray-900 sm:pl-6">{{ index + 1 }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-medium text-gray-900">
                                    <div class="font-bold" :title="construct.objective">{{ construct.name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div v-for="statement in construct.statements">
                                        {{ statement.text }}
                                    </div>
                                </td>
                                <td class="uppercase text-sm text-gray-500">
                                    <a v-if="construct.questionnaires.length === 0" :href="'/constructs/'+construct.id+'/edit'">Edit</a>
                                </td>
                            </tr>
                        </tbody>
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
    props: ['constructs'],
})
</script>
