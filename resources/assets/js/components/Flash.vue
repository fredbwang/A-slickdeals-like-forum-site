<template>
    <div class="alert alert-flash fade show" :class="'alert-' + type" role="alert" v-show="show">
        <strong v-text="phrase"></strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['data'],
        computed: {
            phrase() {
                switch (this.type) {
                    case 'success':
                        return 'Great!';
                    case 'danger':
                        return 'Whoops!';
                    default:
                        return '';
                }
            }
        },
        data() {
            return {
                body: '',
                type: 'success',
                show: false,
            }
        },
        created() {
            // initialize flash body with message when flash card created
            if (this.data) {
                this.flash(this.data);
            }

            // listen for flash events and accept its message
            window.events.$on('flash', data => {
                this.flash(data);
            });
        },
        methods: {
            // flash a card with message
            flash(data) {
                this.body = data.message;
                this.type = data.type ? data.type : this.type;
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