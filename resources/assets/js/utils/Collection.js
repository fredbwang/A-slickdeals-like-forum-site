export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        add(item) {
            this.replies.push(item);

            this.$emit('added');
        },

        remove(index) {
            this.replies.splice(index, 1);

            this.$emit('removed');
        }
    }
}