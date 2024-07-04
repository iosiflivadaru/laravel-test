<script setup>
import { defineEmits } from "vue";

const emits = defineEmits(["update:goToPage"]);
const props = defineProps({
    pagination: Object,
});

function goToPage(page) {
    emits("update:goToPage", page);
}
</script>
<template>
    <div v-if="pagination.data.length > 0" class="flex justify-between items-center mt-4">
        <button :disabled="pagination.current_page === 1" @click="goToPage(pagination.current_page - 1)" class="bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700">
            Previous
        </button>
        <ul class="flex space-x-1">
            <li
                v-for="page in pagination.last_page"
                :key="page"
                class="py-2 px-4 leading-tight bg-gray-800 border border-gray-600 text-gray-400 hover:bg-gray-700 hover:text-white cursor-pointer"
                :class="{ 'bg-blue-600 text-white': page === pagination.current_page }"
                @click="goToPage(page)"
            >
                {{ page }}
            </li>
        </ul>
        <button
            :disabled="pagination.current_page === pagination.last_page"
            @click="goToPage(pagination.current_page + 1)"
            class="bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700"
        >
            Next
        </button>
    </div>
</template>
