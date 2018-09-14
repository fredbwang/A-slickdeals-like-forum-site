<template>
    <div :id="'reply-'+id" class="card" :class="isBest ? 'border-success' : ''">
        <div class="card-header">
            <span class="comment-meta">
                <a class="card-link" :href="'/profiles/'+reply.owner.name" v-text="reply.owner.name">
                </a>
                said
                <span v-text="ago"></span>
            </span>
            <vote :reply="reply"></vote>
        </div>
        <div class="card-body" :class="isBest ? 'text-white bg-success' : ''">
            <div v-show="editting">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="bodyText" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary float-right" type="submit">Submit</button>
                    <button class="btn btn-sm b tn-light float-right mr-1" @click="cancel" type="button">Cancel</button>
                </form>
            </div>

            <div v-show="!editting" v-html="body"></div>
        </div>


        <div class="card-footer" v-if="authorize('canUpdate', reply.thread) || authorize('canUpdate', reply)">
            <button v-if="authorize('canUpdate', reply.thread) && !isBest" class="btn btn-sm float-left " @click="markBest">
                Best Reply?
            </button>
            <div v-if="authorize('canUpdate', reply)">
                <button class="btn btn-sm btn-danger float-right" @click="destroy">Delete</button>
                <button class="btn btn-sm float-right mr-1" @click="edit">Edit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import Vote from './Vote.vue';
    import moment from 'moment';
    import atwho from 'at.js';

    export default {
        props: ["reply"],

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + ":";
            },
        },

        components: {
            Vote
        },

        data() {
            return {
                editting: false,
                id: this.reply.id,
                body: this.reply.body,
                bodyText: "",
                isBest: this.reply.isBest,
            };
        },

        mounted() {
            $('.card-body form textarea').atwho({
                at: '@',
                delay: 500,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {
                                name: query
                            },
                            function (usernames) {
                                callback(usernames);
                            });
                    }
                }
            });
        },

        created() {
            window.events.$on('best-reply-selected', (best_reply_id) => {
                this.isBest = (best_reply_id == this.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                        body: this.bodyText
                    })
                    .then((response) => {
                        this.editting = false;
                        this.body = response.data;
                        flash('Updated!');
                    })
                    .catch((error) => {
                        flash(error.response.data, 'danger');
                    });
            },

            destroy() {
                axios.delete("/replies/" + this.id);

                this.$emit('deleted', this.id);
                // $(this.$el).fadeOut(500, () => {
                //     flash('Comment deleted!');
                // });
            },

            edit() {
                this.editting = true;
                this.bodyText = this.unwrapBody();
            },

            cancel() {
                this.editting = false;
            },

            markBest() {
                axios.post('/replies/' + this.id + '/mark/best');

                window.events.$emit('best-reply-selected', this.id);
            },

            unwrapBody() {
                let pattern = new RegExp('<\s*a[^>]*>(.*?)<\s*/\s*a>');

                return this.body.replace(pattern, '$1');
            },
        }
    };
</script>