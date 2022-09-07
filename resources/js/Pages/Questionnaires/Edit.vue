<template>
    <app-layout title="Questionnaire">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                 <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h2 class="text-xl font-medium leading-6 text-gray-900">Edit Questionnaire</h2>
                    </div>
                 </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg space-y-6 sm:p-6">

                    <form @submit.prevent="$emit('saveQuestionnaire')" class="space-y-6">

                        <div>
                            <jet-label for="title" value="Name" class="block text-sm font-medium text-gray-700" />
                            <jet-input id="title" v-model="form.name" type="text" class="mt-1 block w-full" ref="title" autocomplete="name" />
                            <jet-input-error :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="subject" value="Subject" />
                            <jet-input id="subject" v-model="form.subject" type="text" class="mt-1 block w-full" ref="subject"/>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="starttime" value="Start time" />
                            <el-date-picker
                                v-model="form.start_time"
                                format="DD.MM.YYYY HH:mm"
                                type="datetime"
                                placeholder="Select date and time"
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="endtime" value="End time" />
                            <el-date-picker
                                v-model="form.end_time"
                                format="DD.MM.YYYY HH:mm"
                                type="datetime"
                                placeholder="Select date and time"
                            />
                        </div>

                        <div v-if="this.questionnaire.respondents.length === 0">
                            <jet-label value="Questionnaire constructs" />
                            <div class="text-xs text-gray-700 mb-2">Select all that apply</div>

                            <div class="bg-blue-50 p-4 rounded border border-blue-200">
                                <div v-for="(construct, id) in constructs">
                                    <input type="checkbox" :value="id" v-model="form.constructs" />
                                    <div class="inline-block ml-2">{{construct}} </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="this.questionnaire.respondents.length === 0">
                            <jet-label for="statements" value="Additional questions for students" />
                            <div id="statements" class="bg-blue-50 p-4 rounded border border-blue-200">
                                <div v-for="(statement, index) in form.statements" class="py-1 sm:flex sm:items-center">
                                    <div class="inline-flex items-center mr-2">{{ index + 1 }}</div>
                                    <jet-input type="text" v-model="statement.text" class="mt-1 block w-full" />
                                    <button type="button" v-if="!statement.id" @click="removeStatement(index)" class="inline-flex items-center ml-3 p-1 border border-red-200 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 sm:text-sm">
                                        Delete
                                    </button>
                                </div>
                                <button type="button" @click="addStatement" class="mt-2 inline-flex items-center px-4 py-2 border bg-green-100 text-green-800 border-green-200 shadow-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-150 sm:text-sm">
                                    Add new statement
                                </button>
                            </div>
                        </div>

                        <div>
                            <jet-checkbox v-model="form.log_in_required" :checked="form.log_in_required === 1"/> <jet-label class="inline-block" value="Log in required" />
                        </div>


                        <div class="sm:text-right">
                            <a :href="route('questionnaires.index')" class="hover:bg-gray-100 text-gray-700 mr-2 border rounded px-4 py-2 font-semibold uppercase text-xs">
                                Cancel
                            </a>
                            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="saveQuestionnaire(form)">
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
import JetCheckbox from '@/Jetstream/Checkbox'
import JetInputError from '@/Jetstream/InputError.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetNavLink from '@/Jetstream/NavLink.vue'
import moment from "moment";

export default defineComponent({
    components: {
        AppLayout,
        JetFormSection,
        JetLabel,
        JetInput,
        JetCheckbox,
        JetInputError,
        JetButton,
        JetNavLink,
    },
    props: ['questionnaire', 'constructs', 'questionnaire_constructs'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.questionnaire.id,
                name: this.questionnaire.name,
                subject: this.questionnaire.subject,
                start_time: moment.utc(this.questionnaire.start_time),
                end_time: moment.utc(this.questionnaire.end_time),
                log_in_required: this.questionnaire.log_in_required,
                constructs: this.questionnaire_constructs,
                statements: this.getStatements(this.questionnaire),
            }, {
                bag: 'editQuestionnaire',
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
        saveQuestionnaire(data) {
            console.log(data.constructs);
            this.form.post('/questionnaires/' + data.id, data);
        },
        getStatements(questionnaire) {
            if (questionnaire.statements.length !== 0) {
                return questionnaire.statements;
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
