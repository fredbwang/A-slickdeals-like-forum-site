<template>
    <div class="alert alert-success alert-flash fade show" role="alert" v-show="show">
        <strong>Great!</strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                show: false
            }
        },
        created() {
            // initialize flash body with message when flash card created
            if (this.message) {
                this.flash(this.message);
            }

            // listen for flash events and accept its message
            window.events.$on('flash', message => {
                this.flash(message);
            });
        },
        methods: {
            // flash a card with message
            flash(message) {
                this.body = message;
                this.show = true;
                this.hide();
            },

            // hide flash card after 3 seconds
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 20px;
        bottom: 10px;
    }
</style>