<template>
    <div>
        <div class="row">
            <img :src="avatar_path" alt="No Avatar" width="100" height="100" class="user-avatar mr-3">

            <h3 class="mt-2">
                {{ this.user.name }}
                <br>
                <small> A member since {{ ago }}</small>
                <br>
                <small> Has {{ this.user.visitsCount }} views by now</small>
            </h3>
        </div>

        <div class="input-group row mt-3" v-if="canUpdate">
            <div class="custom-file col-md-4">
                <image-upload class="custom-file-input" v-if="canUpdate" @loaded="onLoad"></image-upload>
                <label class="custom-file-label" for="avatar" v-text="current_file"></label>
            </div>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" @click="persist">Save</button>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import ImageUpload from './ImageUpload.vue';
    export default {
        props: ['user'],

        components: {
            ImageUpload
        },

        data() {
            return {
                avatar_file: false,
                avatar_path: this.user.avatar_path,
                current_file: 'Choose Avatar',
            }
        },

        computed: {
            ago() {
                return moment(this.user.created_at).fromNow();
            },
            canUpdate() {
                return this.authorize(user => {
                    return user.id == this.user.id;
                });
            }
        },

        methods: {
            onLoad(avatar_data) {
                this.avatar_path = avatar_data.src;
                this.avatar_file = avatar_data.file;
                this.current_file = avatar_data.file.name;
            },
            persist() {
                let data = new FormData();

                data.append('avatar', this.avatar_file);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => {
                        flash('Avatar Saved!');
                    })
                    .catch(() => {
                        flash('Avatar Uploading Failed!', 'danger');
                    });
            }
        }

    }
</script>

<style>
    img.user-avatar {
        border-radius: 50px;
        border: double 1px black;
    }
</style>