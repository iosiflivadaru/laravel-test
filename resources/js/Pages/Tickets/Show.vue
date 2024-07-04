<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    ticket: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="text-white mt-10 text-xl space-y-2">
            <p>
                Title: <span class="text-gray-400">{{ ticket.title }}</span>
            </p>
            <p>
                Description: <span class="text-gray-400">{{ ticket.description }}</span>
            </p>
            <p>
                Priority: <span class="text-gray-400">{{ ticket.priority }}</span>
            </p>
            <p>
                Status: <span class="text-gray-400">{{ ticket.status }}</span>
            </p>
        </div>
    </AuthenticatedLayout>
</template>
