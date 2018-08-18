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
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-primary float-right" @click="update">Submit</button>
                <button class="btn btn-sm btn-light float-right mr-1" @click="cancel">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
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

                this.editting = false;

                flash('Updated!');
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