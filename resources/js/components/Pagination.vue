<template>
    <nav
        class="flex justify-between w-full"
        aria-label="Page navigation example"
    >
        <p class="mt-2 text-gray-600 text-sm">
            Showing
            {{ paginationData.current_page }}
            of
            {{
                Math.ceil(
                    this.paginationData.total / this.paginationData.per_page
                )
            }}
            of {{ paginationData.total }} 
        </p>

        <ul class="inline-flex -space-x-px text-sm">
            <li>
                <a
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    :class="{
                        'cursor-not-allowed ':
                            paginationData.current_page === 1,
                    }"
                    @click.prevent="
                        paginationData.current_page !== 1 &&
                            changePage(paginationData.current_page - 1)
                    "
                    href="#"
                >
                    Previous
                </a>
            </li>
            <li v-for="page in paginationData.last_page" :key="page">
                <a
                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer"
                    :class="{
                        'bg-gray-300 text-wn-primary hover:bg-blue-100 hover:text-wn-primary':
                            paginationData.current_page === page,
                    }"
                    @click.prevent="changePage(page)"
                >
                    {{ page }}
                </a>
            </li>
            <li>
                <a
                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    :class="{
                        'cursor-not-allowed':
                            paginationData.current_page ===
                            paginationData.last_page,
                    }"
                    @click.prevent="
                        paginationData.current_page !==
                            paginationData.last_page &&
                            changePage(paginationData.current_page + 1)
                    "
                    href="#"
                >
                    Next
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: {
        paginationData: {
            type: Object,
            required: true,
        },
    },
    methods: {
        changePage(pageNumber) {
            this.$emit("page-changed", pageNumber);
        },
    },
};
</script>
