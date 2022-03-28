<template>
    <app-layout title="Constructs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create new Questionnaire
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            <datepicker class="w-1/4" v-model="form.start_time" format="dd.MM.yyyy HH:mm" utc />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="endtime" value="End time" />
                            <datepicker class="w-1/4" v-model="form.end_time" format="dd.MM.yyyy HH:mm" utc />
                        </div>

                        <div>
                            <jet-label value="Questionnaire constructs" />
                            <div class="text-xs text-gray-700 mb-2">Select all that apply</div>
                            <div class="bg-blue-50 p-4 rounded border border-blue-200">
                                <div v-for="(construct, id) in constructs">
                                    <input type="checkbox" :value="id" v-model="form.constructs" />
                                    <div class="inline-block ml-2">{{construct}} </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <jet-checkbox /> <jet-label class="inline-block" value="Log in required" />
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
import Datepicker from 'vue3-date-time-picker'
import 'vue3-date-time-picker/dist/main.css'

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
        Datepicker,
    },
    props: ['constructs'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'POST',
                name: '',
                subject: '',
                start_time: '',
                end_time: '',
                constructs: [],
            }, {
                bag: 'createQuestionnaire',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        saveQuestionnaire(data) {
            console.log('save questionnaire');
            console.log(data);
            console.log(data.constructs);
            this.form.post(route('questionnaires.index'), data);
        },
    },
})
</script>
