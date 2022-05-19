<template>
    <nav v-if="links.length > 3" class="border-t border-gray-200 px-4 flex items-center justify-between sm:px-0">
        <div class="hidden md:-mt-px md:flex">
            <template v-for="(link, key) in links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="
                          border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="
                        border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium"
                    :class="{ 'border-indigo-500 text-indigo-600': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        Link,
    },

    props: {
        links: Array,
    },
};
</script>
