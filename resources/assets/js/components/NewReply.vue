<template>
    <div>
        <div v-if="signedIn">
            <form @submit="submitReply">
                <div class="form-group">
                    <label for="body">Reply:</label>
                    <textarea class="form-control" name="body" id="body" rows="10" v-model="body" required placeholder="Say something about the deal?"></textarea>
                </div>
                <button class="btn btn-default" type="submit">Submit</button>
            </form>
        </div>

        <div v-else class="row justify-content-center">
            <p>
                <a href="/login">Sign in</a> to reply!
            </p>
        </div>
    </div>
</template>
<script>
    import atwho from 'at.js';
    import 'jquery.caret';

    export default {
        props: ['endpoint'],

        data() {
            return {
                body: "",
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 500,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users",
                            {name: query},
                            function (usernames) {
                                callback(usernames);
                            });
                    }
                }
            });
        },

        methods: {
            submitReply() {
                axios.post(this.endpoint, {
                        body: this.body
                    })
                    .then((response) => {
                        this.body = "";
                        this.$emit('created', response.data);
                    })
                    .catch((error) => {
                        flash(error.response.data, 'danger');
                    });
            }
        }
    }
</script>