let user = window.App.user;

module.exports = {
    // updateReply(reply) {
    //     return reply.user_id == user.id;
    // },

    // updateThread(thread) {
    //     return thread.user_id == user.id;
    // },

    canUpdate(object, reference = 'user_id') {
        return object[reference] == user.id;
    },

    isAdmin() {
        return ['JohnDoe'].includes(user.name);
    }
};