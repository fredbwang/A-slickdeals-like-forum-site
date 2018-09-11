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
                repliesCount: this.dataThread.replies_count,
                locked: this.dataThread.locked,
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
            }
        }
    }
</script>