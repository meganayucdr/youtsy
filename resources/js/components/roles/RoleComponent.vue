<template>
<!--    <v-flex md10 offset-md1>-->
<!--        <v-card>-->
<!--            <v-data-table-->
<!--                :headers="headers"-->
<!--                :items="items"-->
<!--                hide-actions-->
<!--                class="elevation-1"-->
<!--            >-->
<!--                <template slot="items" slot-scope="props">-->
<!--                    <td class="text-xs-left">{{ props.item.role }}</td>-->
<!--                </template>-->
<!--            </v-data-table>-->
<!--        </v-card>-->
<!--    </v-flex>-->
    <div>
        <table class="table">
            <thread>
                <tr>
                    <th scope="col"> Role </th>
                </tr>
            </thread>
            <tbody>
                <tr v-if="tableData.length === 0">
                    <td class="lead text-center" colspan="4">No data found</td>
                </tr>
                <tr v-for="data in tableData" v-else>
                    <td>{{ data.role }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios'
    import _ from 'lodash'
    export default {
        name: "RoleComponent",
        data: () => ({
            tableData: [],
            page: 0,
            lastpage: 1,
            lodash: _,
            total: '',
            token: '',
            email: '',
            password: ''
        }),

        created()   {
            this.fetchData();
        },

        methods: {
            requestToken()  {
                const URL = '/oauth/token';
                // axios.get('/api/user')
                //     .then(response => {
                //     this.email = response.name;
                //     this.password = response.password;
                // });
                axios.put(URL, {
                    grant_type: 'password',
                    client_id: '2',
                    client_secret: '6cNR42tv8HHvgMCfztwNS8lGw68WP4hro4XQxKYk',
                    username: this.localStorage.getItem("email"),
                    password: this.localStorage.getItem("password")
                })
                    .then(response => {
                        console.log(response);
                        this.$cookie.set('api_token', response.data.access_token, 1);
                    })
                    .catch()
            },

            fetchData() {
                this.requestToken();
                this.page += 1;
                const URL = '/api/role';
                axios.get(URL, {
                    params: {
                        page: this.page
                    },
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'Authorization' : 'Bearer ' + this.$cookie.get('api_token')
                    }
                })
                    .then( r => r.data )
                    .then( tableData => {
                        const temp = this.tableData.concat(tableData.data);
                        this.tableData = temp;
                        this.lastpage = tableData.meta.last_page;
                        this.total = tableData.meta.total;
                    })
                    .catch( err => {
                        this.page = -1;
                    })
            }
        }

    }
</script>

<style scoped>

</style>
