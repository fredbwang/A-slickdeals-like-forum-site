<template>
    <div>
        <div :key="reply.id" v-for="(reply, index) in replies">
            <reply :data="reply" @deleted="remove(index)"></reply>
            <br>
        </div>

        <paginator :dataSet="dataSet" @pageChanged="fecth"></paginator>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";

    export default {
        components: {
            Reply,
            NewReply
        },

        computed: {},

        data() {
            return {
                dataSet: false,
                replies: [],
                endpoint: location.pathname + '/replies',
            };
        },

        created() {
            this.fecth();
        },

        methods: {
            fecth(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            url(page) {
                if (!page) {
                    let query = location.search.match(/page\=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return location.pathname + '/replies?page=' + page;
            },

            refresh(response) {
                let data = response.data; // get response data
                this.dataSet = data;
                this.replies = data.data;

            },

            add(reply) {
                this.replies.push(reply);

                this.$emit('added');

                flash('Your have commented on this deal!');
            },

            remove(index) {
                this.replies.splice(index, 1);

                this.$emit('removed');

                flash('Comment deleted!');
            }
        }
    };
</script>