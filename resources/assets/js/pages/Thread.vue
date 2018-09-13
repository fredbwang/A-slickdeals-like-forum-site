<script>
    import Replies from '../components/Replies.vue';
    import SubscribeBtn from '../components/SubscribeBtn.vue';
    export default {
        props: ['dataThread'],
        components: {
            Replies,
            SubscribeBtn
        },
        data() {
            return {
                thread: this.dataThread,
                title: this.dataThread.title,
                body: this.dataThread.body,
                repliesCount: this.dataThread.replies_count,
                locked: this.dataThread.locked,
                editting: false,
            }
        },

        methods: {
            lock() {
                this.locked = true;

                axios.post(window.location.pathname + '/lock', {
                    'lock': true
                });
            },

            unlock() {
                this.locked = false;

                axios.post(window.location.pathname + '/lock', {
                    'lock': false
                });
            },

            submit() {
                axios.patch(this.thread.path, {
                    'title': this.title,
                    'body': this.body
                }).then(() => {
                    flash('Your deal is updated!');
                    this.editting = false;
                }).catch((error) => { 
                    let message = this.getMessage(error.response.data);
                    flash(message, 'danger');
                });

            },

            edit() {
                this.editting = true;
            },

            cancel() {
                this.editting = false;
                this.body = this.dataThread.body;
                this.title = this.dataThread.title;
            },

            getMessage(data) {
                let bodyError = data.errors.body == null ? '' : data.errors.body;
                let titleError = data.errors.title == null ? '' : data.errors.title;
                return bodyError + " " + titleError;
            }
        }
    }
</script>