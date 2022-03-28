<template>

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div>
                <div class="text-2xl">Thank you for your feedback!</div>
                <div>You can close this page now.</div>
            </div>


        </div>
    </div>

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
})
</script>
