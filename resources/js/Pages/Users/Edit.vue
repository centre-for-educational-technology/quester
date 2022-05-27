<template>
    <app-layout>

        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Edit User
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg space-y-6 sm:p-6">

                    <form @submit.prevent="$emit('saveUser')" class="space-y-6">

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Name" class="block text-sm font-medium text-gray-700" />
                            {{ selected_user.name}}
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="email" value="Email" class="block text-sm font-medium text-gray-700" />
                            {{ selected_user.email }}
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="role" value="Role" class="block text-sm font-medium text-gray-700" />
                            <input type="radio" value="" v-model="form.role" /> student
                            <div v-for="role in roles">
                                <input type="radio" :value="role.name" v-model="form.role"/>  {{ role.name }}
                            </div>
                        </div>

                        <div class="sm:text-right">
                            <a :href="route('users.index')" class="hover:bg-gray-100 text-gray-700 mr-2 border rounded px-4 py-2 font-semibold uppercase text-xs">
                                Cancel
                            </a>
                            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="updateUser(form)">
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
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetButton from '@/Jetstream/Button.vue'

export default defineComponent({
    components: {
        AppLayout,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
    },
    props: ['selected_user', 'roles', 'user_role'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.selected_user.id,
                name: this.selected_user.name,
                email: this.selected_user.email,
                role: this.user_role,
            }, {
                bag: 'updateUser',
            }),
        }
    },

    methods: {
        updateUser(data) {
            this.form.post('/users/' + data.id, data);
        },
    },
})
</script>
