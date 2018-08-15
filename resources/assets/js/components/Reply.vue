<script>
    import Vote from './Vote.vue';

    export default {
        props: ["reply"],

        components: {
            Vote
        },

        data() {
            return {
                editting: false,
                body: this.reply.body
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.reply.id, {
                    body: this.body
                })

                this.editting = false;

                flash('Updated!');
            },
            destroy() {
                axios.delete("/replies/" + this.reply.id);

                $(this.$el).fadeOut(500, () => {
                    flash('Comment deleted!');
                });
            },
            cancel() {
                this.editting = false;
                this.body = this.reply.body;
            }
        }
    };
</script>