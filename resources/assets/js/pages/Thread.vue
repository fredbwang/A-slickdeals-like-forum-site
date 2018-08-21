<script>
    import Replies from '../components/Replies.vue';
    import SubscribeBtn from '../components/SubscribeBtn.vue';
    export default {
        props: ['initialRepliesCount'],
        components: {
            Replies, SubscribeBtn
        },
        data() {
            return {
                repliesCount: this.initialRepliesCount,
            }
        }
    }
</script>