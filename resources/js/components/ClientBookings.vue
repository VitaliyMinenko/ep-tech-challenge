<template>
    <div class="bg-white rounded p-4">
        <h3 class="mb-3">List of client bookings</h3>
        <template v-if="localFilteredBookings.length > 0">
            <div class="flex justify-between items-center mb-4">
                <span>Filter by:</span>
                <div class="relative">
                    <select
                        class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        v-model="bookingFilter"
                        @change="filterBookings">
                        <option value="all">All bookings</option>
                        <option value="future">Future bookings only</option>
                        <option value="past">Past bookings only</option>
                    </select>
                </div>
            </div>
            <table>
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="booking in localFilteredBookings" :key="booking.id">
                    <td>{{ formatBookingTime(booking.start, booking.end) }}</td>
                    <td>{{ booking.notes }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </template>
        <template v-else>
            <p class="text-center">The client has no bookings.</p>
        </template>
    </div>
</template>

<script>
export default {
    props: ['bookings'],
    data() {
        return {
            bookingFilter: 'all',
            localFilteredBookings: [],
        };
    },
    mounted() {
        this.localFilteredBookings = [...this.bookings];
        this.filterBookings();
    },
    watch: {
        bookings: {
            immediate: true,
            handler(newBookings) {
                this.localFilteredBookings = [...newBookings];
                this.filterBookings();
            },
        },
    },
    methods: {
        filterBookings() {
            const now = new Date();
            this.localFilteredBookings = this.localFilteredBookings.filter(booking => {
                const bookingStart = new Date(booking.start);
                switch (this.bookingFilter) {
                    case 'future':
                        return bookingStart > now;
                    case 'past':
                        return bookingStart < now;
                    case 'all':
                    default:
                        return true;
                }
            });
        },
        formatBookingTime(start, end) {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            };
            const startDate = new Date(start);
            const endDate = new Date(end);
            return `${startDate.toLocaleString('en-US', options)} to ${endDate.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            })}`;
        },
        deleteBooking(booking) {
            axios.delete(`/bookings/${booking.id}`).then(() => {
                this.localFilteredBookings = this.localFilteredBookings.filter(b => b.id !== booking.id);
                this.filterBookings();
            });
        },
    },
};
</script>
