<template>
    <div class="vote-section float-right" v-if="signedIn">
        <button type="submit" :class="upVoteClasses" @click="toggleVote('vote-up')">
            <i class="fa fa-thumbs-up"></i>
            <span v-text="upVotesCount"></span>
        </button>
        <button type="submit" :class="downVoteClasses" @click="toggleVote('vote-down')">
            <i class="fa fa-thumbs-down"></i>
            <span v-text="downVotesCount"></span>
        </button>
    </div>
</template>
<script>
    export default {
        props: ['reply'],
        computed: {
            upVoteClasses() {
                return [
                    'mr-1', 'btn', 'btn-sm',
                    this.currentVote > 0 ? 'btn-primary' : 'btn-light'
                ];
            },
            downVoteClasses() {
                return [
                    'mr-1', 'btn', 'btn-sm',
                    this.currentVote < 0 ? 'btn-secondary' : 'btn-light'
                ];
            },
            currentType() {
                return this.currentVote > 0 ? 'vote-up' : this.currentVote < 0 ? 'vote-down' : 'no-vote';
            },
            signedIn() {
                return window.App.signedIn;
            }
        },
        data() {
            return {
                downVotesCount: this.reply.downVotesCount,
                upVotesCount: this.reply.upVotesCount,
                currentVote: this.reply.currentVote,
            }
        },
        methods: {
            toggleVote(type) {
                if (this.currentType == 'no-vote') {
                    this.vote(type);
                } else if (this.currentType == type) {
                    this.cancel(type);
                } else {
                    this.switch(type);
                }
            },
            switch (type) {
                this.vote(type);
                if (type == 'vote-up') {
                    this.downVotesCount--;
                } else if (type == 'vote-down') {
                    this.upVotesCount--;
                }
            },
            cancel(type) {
                axios.post('/replies/' + this.reply.id + '/vote/cancel-vote');
                this.currentVote = 0;
                type == 'vote-up' ?
                    this.upVotesCount-- :
                    this.downVotesCount--;
            },
            vote(type) {
                axios.post('/replies/' + this.reply.id + '/vote/' + type);
                this.currentVote = type == 'vote-up' ? 1 : -1;
                type == 'vote-up' ?
                    this.upVotesCount++ :
                    this.downVotesCount++;
            },
        }
    }
</script>