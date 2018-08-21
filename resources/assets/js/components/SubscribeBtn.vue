<template>
    <button :class="classes" @click='toggleSubscribe' v-text="btnText"></button>
</template>

<script>
    export default {
        props: ['initialActive'],

        computed: {
            classes() {
                return ['btn', 'btn-' + (this.active ? 'info' : 'default')];
            },
            btnText() {
                return this.active ? 'Subscribed' : 'Subscribe';
            }
        },

        data() {
            return {
                active: this.initialActive,
            };
        },

        methods: {
            toggleSubscribe() {
                let requestType = this.active ? 'delete' : 'post';
                axios[requestType](location.pathname + '/subscribe')
                    .then((e) => {
                        this.active = !this.active;

                        let message = this.active ? 'subscribed to' : 'unsubscribed from';
                        flash('You have ' + message + ' this thread!');
                    });
            }
        }
    }
</script>