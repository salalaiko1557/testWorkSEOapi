<template>
    <v-app>
        <v-alert
            v-if="modal"
            :value="true"
            type="success"
            class="font-bolder alert white--text"
        >
            {{message}}
        </v-alert>
        <section class="flex start">
            <v-card>
                <v-card-title class="font-bolder">
                    Tasks
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                </v-card-title>
                <v-data-table
                :headers="headers"
                :items="database"
                :search="search"
                >
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.id}}</td>
                    <td class="text-xs-center">{{ props.item.task_id }}</td>
                    <td class="text-xs-center">
                        <v-icon @click="showMore(props.item.task_id)">fast_forward</v-icon>
                    </td>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
                </v-data-table>
            </v-card>
            <div class="column">
                <v-container v-if="response">
                    <p>Tasks count: {{response.results_count}}</p>
                    <p>Query time: {{response.results_time}}</p>
                    <p>Server status: {{response.status}}</p>
                </v-container>
                <v-container v-if="result">
                    <div v-for="item in result.organic" :key="item.task_id">
                        <p>Post id: {{item.post_id}}</p>
                        <p>Task id: {{item.task_id}}</p>
                        <p>SE id: {{item.se_id}}</p>
                        <p>Loc id: {{item.loc_id}}</p>
                        <p>Key id: {{item.key_id}}</p>
                        <p>Post key: {{item.post_key}}</p>
                        <p>Post site: {{item.post_site}}</p>
                        <p>Datetime: {{item.result_datetime}}</p>
                        <p>Snippet: {{item.result_snippet}}</p>
                        <p>Snippet extra: {{item.result_snippet_extra}}</p>
                        <p>Spell: {{item.result_spell}}</p>
                        <p>Spell type: {{item.result_spell_type}}</p>
                        <p>Title: {{item.result_title}}</p>
                        <p>Result Url: {{item.result_url}}</p>
                        <p>Count: {{item.results_count}}</p>
                        <p>Extra: {{item.result_extra}}</p>
                        <p>Position: {{item.result_position}}</p>
                        <p>Url: <a target="_blank" :href="item.result_se_check_url">{{item.result_se_check_url}}</a></p>

                    </div>
                </v-container>
            </div>
        </section>
    </v-app>
</template>
<script>
export default {
    data(){
        return{
            result: {},
            response: {},
            database: [],
            message:  '',
            modal: false,
            search: '',
            headers: [
                { text: 'id', value: 'id', align: 'center'},
                { text: 'Task ID', value: 'task_id', align: 'center'},
                { text: 'Show', align: 'center'},
            ],
        }
    },
    methods: {
        /**
         *
         * set task_id (number)
         * get task (object)
         *
         */
        showMore(task_id){
            axios
            .post('/api/single', {
                data: task_id
            })
            .then(response => {
                this.result = response.data.results;
                this.response = response.data;
            })
            .catch(error => {
                this.modal = true;
                this.message = error.message;
            });
        }
    },
    created(){
        axios
        /**
         *
         * get tasks (json)
         *
         */
            .get('/api/get')
            .then(response => {
                this.database = response.data
            })
            .catch(error => {
                this.modal = true;
                this.message = error.message;
            });
    }
}
</script>
<style>
.items{
    color: black;
    font-weight: 500;
}
.start{
    align-items: stretch;
    justify-content: flex-start;
}
.column{
    /* display: flex; */
    /* flex-direction: column; */
}
</style>
