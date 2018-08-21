<template>
    <div id="notification-dropdown" class="nav-item dropdown" v-if="notifications.length">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            <span class="fa fa-bell fa-lg"></span>
            <span id="notifications-badge" class="badge badge-pill badge-primary position-relative" v-text="notifications.length"></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-right">
            <li :key="notification.id" v-for="(notification, index) in notifications">
                <a class="dropdown-item" :id="'notification-'+notification.id" @click="markAsRead(notification)" :href="notification.data.link"
                    v-html="notificationContent(notification)">
                </a>
            </li>
        </ul>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => {
                    console.log(response.data);
                    this.notifications = response.data;
                });
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            },
            notificationContent(notification) {
                return `<i class="fa fa-circle fa-xs"></i> ` + notification.data['message-short'];
            }
        }
    }
</script>