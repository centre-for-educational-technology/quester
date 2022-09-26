<template>
    <app-layout title="Constructs">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                 <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h2 class="text-xl font-medium leading-6 text-gray-900">Edit Construct</h2>
                    </div>
            
                 </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg space-y-6 sm:p-6 pt-10 mt-10">
                    <form @submit.prevent="$emit('saveConstruct')" class="space-y-6">
                        <div>
                            <jet-label for="title" value="Name" class="block text-sm font-medium text-gray-700" />
                            <jet-input id="title" v-model="form.name" type="text" class="mt-1 block w-full" ref="title" autocomplete="title" />
                            <jet-input-error :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="objective" value="Objective" />
                            <jet-input id="objective" v-model="form.objective" type="text" class="mt-1 block w-full" ref="objective"/>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="statements" value="Statements" />
                            <div id="statements" class="bg-blue-50 p-4 rounded border border-blue-200">
                                <div v-for="(statement, index) in form.statements" class="py-1 sm:flex sm:items-center">
                                    <div class="inline-flex items-center mr-2">{{ index + 1 }}</div>
                                    <jet-input type="text" v-model="statement.text" class="mt-1 block w-full" />
                                    <button type="button" v-if="!statement.id" @click="removeStatement(index)" class="inline-flex items-center ml-3 p-1 border border-red-200 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 sm:text-sm">
                                        Delete
                                    </button>
                                </div>
                                <button type="button" @click="addStatement" class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm font-medium rounded-md text-white hover:bg-indigo-500 bg-indigo-600 hover:bg-gray-150 sm:text-sm">
                                    Add new statement
                                </button>
                            </div>
                        </div>

                        <div class="sm:text-right">
                            <a :href="route('constructs.index')" class="hover:bg-gray-100 text-gray-700 mr-2 border rounded px-4 py-2 font-semibold uppercase text-xs">
                                Cancel
                            </a>
                            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="saveConstruct(form)">
                                Save
                            </jet-button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetNavLink from '@/Jetstream/NavLink.vue'

export default defineComponent({
    components: {
        AppLayout,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
        JetNavLink,
    },
    props: ['construct'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.construct.id,
                name: this.construct.name,
                objective: this.construct.objective,
                statements: this.getStatements(this.construct),
            }, {
                bag: 'editConstruct',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        addStatement() {
            this.form.statements.push({
                text: ''
            });
        },
        removeStatement(index) {
            this.form.statements.splice(index, 1);
        },
        saveConstruct(data) {
            this.form.post('/constructs/' + data.id, data);
        },
        getStatements(construct) {
            if (construct.statements.length !== 0) {
                return construct.statements;
            }
            return [
                { text: '' },
                { text: '' },
                { text: '' }
            ];
        },
    },
})
</script>
