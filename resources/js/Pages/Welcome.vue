<template>

    <Head title="Quester" />

    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div>
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start" aria-label="Global">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-between w-full md:w-auto">
                                    <a href="#">
                                        <span class="sr-only">Workflow</span>
                                        <img alt="Workflow" class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg">
                                    </a>
                                </div>
                            </div>
                            <div v-if="canLogin" class="md:block md:ml-10 md:pr-4 md:space-x-8">

                                    <a v-if="$page.props.user" href="/constructs" class="text-sm text-gray-700 underline">Dashboard</a>

                                    <template v-else>

                                        <Link v-if="canRegister" :href="route('register')" class="font-medium text-gray-500 hover:text-gray-900">
                                            Register
                                        </Link>

                                        <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Log in
                                        </Link>

                                        <!--<a :href="route('auth.harid.redirect')" class="ml-4 text-sm text-gray-700 underline">
                                            HarID
                                        </a>-->

                                    </template>

                            </div>
                        </nav>
                    </div>

                </div>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Teacher's feedback </span>
                            <span class="block xl:inline">tool <span class="xl:inline text-indigo-600">Quester</span></span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Quester is a tool for collecting feedback on your lesson. Please answer the questions so that the teacher can plan his/her lessons even better.
                        </p>
                        <div class="font-bold mb-3 mt-10 text-lg">Enter pin to get started</div>
                        <div>
                            <input required class="appearance-none rounded-md border ring-1 ring-gray-200 border-gray-200 focus:ring-gray-500 focus:border-gray-500 focus-outline-none" type="text" v-model="this.questionnaire_code"/>
                            <button @click="startQuestionnaire()" class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Start</button>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="">
        </div>
    </div>

    <div v-if="$page.props.user && laravelVersion && phpVersion">
        <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            Link,
        },

        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        },
        data() {
            return {
                questionnaire_code: '',
            }
        },
        methods: {
            startQuestionnaire() {
                let form = this.$inertia.form({
                    '_method': 'GET',
                    questionnaire_code: this.questionnaire_code,
                }, {
                    bag: 'startQuestionnaire'
                });
                form.post(route('questionnaires.start'));
            },
        },
    })
</script>
