<script setup>
import Pagination from "@/Components/Pagination.vue";
import formatDate from "@/Utilities/formatDate";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

const props = defineProps({
    tickets: Object,
});

let allTickets = ref([]);
let pagination = ref({});

const search = ref("");
const priority = ref("");
const status = ref("");
const date_range = ref([]);

allTickets.value = props.tickets.data;
pagination.value = props.tickets;

watch(search, async () => {
    fetchTickets();
});

watch(date_range, async () => {
    fetchTickets();
});

function fetchTickets(page = 1) {
    axios.post(route("api.search.tickets"), { search: search.value, priority: priority.value, status: status.value, date: date_range.value, page: page }).then((resp) => {
        allTickets.value = resp.data.data;
        pagination.value = resp.data;
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex justify-between items-center py-6">
            <h1 class="text-2xl font-semibold text-gray-100">Tickets</h1>
            <div class="flex items-end justify-end gap-4 w-full">
                <div class="w-62">
                    <VueDatePicker
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white"
                        placeholder="Search by Date"
                        v-model="date_range"
                        :enable-time-picker="false"
                        :range="true"
                        @change="fetchTickets()"
                    ></VueDatePicker>
                </div>
                <select
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white"
                    @change="fetchTickets()"
                    v-model="priority"
                >
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <select
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white"
                    @change="fetchTickets()"
                    v-model="status"
                >
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
                <input class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-700 text-white w-80" type="text" v-model="search" />
                <a
                    href="/tickets/create"
                    class="whitespace-nowrap inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Create Ticket
                </a>
            </div>
        </div>
        <div class="overflow-x-auto shadow sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Submitted</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Priority</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">&nbsp;</th>
                    </tr>
                </thead>
                <tbody v-if="allTickets.length > 0" class="divide-y divide-gray-700">
                    <tr v-for="ticket in allTickets" :key="ticket.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ formatDate(ticket.created_at) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">{{ ticket.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.user.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.priority }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ ticket.status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <Link :href="route('tickets.show', [ticket.id])"> View </Link>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td class="text-gray-400 text-center pt-4" colspan="7">No results found!</td>
                    </tr>
                </tbody>
            </table>
            <Pagination :pagination="pagination" @update:goToPage="fetchTickets($event)" />
        </div>
    </AuthenticatedLayout>
</template>
