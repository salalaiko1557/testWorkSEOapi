<template>
  <v-app>
        <v-container class="find-block">
            <v-alert
                v-if="modal"
                :value="true"
                type="success"
                class="font-bolder alert white--text"
            >
                {{message}}
            </v-alert>

            <form @submit.prevent="onSubmit()" class="flex">
                <v-text-field
                v-model="input"
                label="Search field"
                required
                ></v-text-field>
                <v-btn icon flat color="black" type="submit">
                    <v-icon>search</v-icon>
                </v-btn>
            </form>
        </v-container>
  </v-app>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            input: '',
            message:  '',
            modal: false

        }
    },
    methods: {
        /**
         *
         * Submited form and send user string
         *
         */
        onSubmit(){
            axios
                .post('/api/set', {
                    data: this.input
                })
                .then(response => {
                   this.message = response.data;
                   this.modal = true;
                   setTimeout(() => this.modal = false, 3000);
                })
                .catch(error => {
                   this.modal = true;
                   this.message = error.message;
                });
        },

    }
}
</script>
<style>
.find-block{
    display: flex;
    align-items: center;
    justify-content: center;
}
.flex{
    display: flex;
}
.font-bolder{
    font-weight: 500;
}
.alert{
    border-radius: 3rem;
    position: absolute;
    top: 1rem;
    right: 1rem;
    min-width: 5rem;
}
</style>
