<template>
    <div :id="'reply-'+id" class="card">
        <div class="card-header">
            <span class="comment-meta">
                <a class="card-link" :href="'/profiles/'+data.owner.name" v-text="data.owner.name">
                </a>
                said
                <span v-text="ago"></span>
            </span>
            <vote :reply="data"></vote>
        </div>
        <div class="card-body">
            <div v-if="editting">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary float-right" type="submit">Submit</button>
                    <button class="btn btn-sm b tn-light float-right mr-1" @click="cancel" type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>


        <div class="card-footer" v-if="canUpdate">
            <button class="btn btn-sm btn-danger float-right" @click="destroy">Delete</button>
            <button class="btn btn-sm float-right mr-1" @click="editting=true">Edit</button>
        </div>
    </div>
</template>

<script>
    import Vote from './Vote.vue';
    import moment from 'moment';
    import atwho from 'at.js';

    export default {
        props: ["data"],

        computed: {
            canUpdate() {
                return this.authorize((user) => this.data.user_id == user.id);
            },
            ago() {
                return moment(this.data.created_at).fromNow() + ":";
            }
        },

        components: {
            Vote
        },

        data() {
            return {
                editting: false,
                id: this.data.id,
                body: this.data.body
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                        body: this.body
                    })
                    .then((response) => {
                        this.editting = false;
                        flash('Updated!');
                    })
                    .catch((error) => {
                        flash(error.response.data, 'danger');
                    });
            },
            destroy() {
                axios.delete("/replies/" + this.data.id);

                this.$emit('deleted', this.data.id);
                // $(this.$el).fadeOut(500, () => {
                //     flash('Comment deleted!');
                // });
            },
            cancel() {
                this.editting = false;
                this.body = this.data.body;
            }
        }
    };
</script>