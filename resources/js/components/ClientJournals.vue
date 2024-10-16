<template>
    <div class="bg-white rounded p-4 w-full">
        <h3 class="mb-3">List of client journals</h3>
        <template v-if="localJournals.length > 0">
            <table class="w-full table-auto border-collapse">
                <thead>
                <tr class="border-b">
                    <th class="text-left py-2 px-4">Date</th>
                    <th class="text-left py-2 px-4">Text</th>
                    <th class="text-left py-2 px-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="journal in localJournals" :key="journal.id" class="border-b">
                    <td class="py-2 px-4">{{ journal.date }}</td>
                    <td class="py-2 px-4">{{ journal.text }}</td>
                    <td class="py-2 px-4">
                        <button class="btn btn-danger btn-sm" @click="deleteJournal(journal)">Delete</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </template>
        <template v-else>
            <p class="text-center">The client has no journals.</p>
        </template>

        <div>
            <textarea class="w-full border p-2 mt-4" rows="5" v-model="journalText"></textarea>
        </div>
        <div>
            <button class="btn btn-primary btn-sm mt-2" @click="addNewJournal()">Add New Journal</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['journals', 'client'],
    data() {
        return {
            journalText: '',
            localJournals: [...this.journals],
        };
    },
    watch: {
        journals: {
            immediate: true,
            handler(newJournals) {
                this.localJournals = [...newJournals];
            },
        },
    },
    methods: {
        addNewJournal() {
            const today = new Date();
            const currentDate = today.toISOString().split('T')[0];
            axios.post('/journals', {
                text: this.journalText,
                date: currentDate,
                client_id: this.client.id,
            })
                .then((response) => {
                    this.localJournals.push(response.data);
                    this.journalText = '';
                })
                .catch(error => {
                    console.error(error);
                });
        },
        deleteJournal(journal) {
            axios.delete(`/journals/${journal.id}`).then(() => {
                this.localJournals = this.localJournals.filter(j => j.id !== journal.id);
            });
        },
    },
};
</script>
