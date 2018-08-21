<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <label for="body">Reply:</label>
                <textarea class="form-control" name="body" id="body" rows="10" v-model="body" required placeholder="Say something about the deal?"></textarea>
            </div>
            <button class="btn btn-default" type="submit" @click="submitReply">Submit</button>
        </div>

        <div v-else class="row justify-content-center">
            <p>
                <a href="/login">Sign in</a> to reply!
            </p>
        </div>
    </div>
</template>
<script>
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

        methods: {
            submitReply() {
                axios.post(this.endpoint, {
                        body: this.body
                    })
                    .then((response) => {
                        console.log(response);
                        this.body = "";
                        this.$emit('created', response.data);
                    });
            }
        }
    }
</script>