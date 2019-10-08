<template>
    <b-table
        show-empty
        small
        stacked="md"
        :items="roles"
        :fields="fields"
    >
    </b-table>
</template>

<script>
    import axios from 'axios'
    export default {
        name: "RoleComponent",
        data: () => ({
            alert: false,
            valid: true,
            message: '',
            search: '',
            key: '',
            dialog: false,
            fields: [
                { key: 'role', label: 'Role' }
            ],
            roles: [],
            editedIndex: -1,
            editedItem: {
                id: '',
                role: ''
            },
            defaultItem: {
                id: '',
                role: ''
            }
        }),

        watch: {
            dialog(val) {
                val || this.close()
            }
        },

        created ()  {
            this.fetchItem()
        },

        methods: {
            fetchItem()     {
                const URL = '/api/roles'
                axios.get(URL, {
                    headers: {
                        'Authorization': 'Bearer ' + this.$store.state.auth.token
                    }
                })
                    .then(response => {
                        this.roles = response.data.data
                    })
                    .catch( err=> {} )
            }
        }
    }
</script>

<style scoped>

</style>
