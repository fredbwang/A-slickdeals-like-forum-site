<template>
    <input type="file" name="avatar" id="avatar" accept="image/*" @change="onChange">
</template>
<script>
    export default {
        data() {
            return {
                file: false,
            };
        },
        methods: {
            onChange(e) {
                if (!e.target.files) return;

                this.file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(this.file);

                reader.onload = e => {
                    this.$emit('loaded', {
                        src: e.target.result,
                        file: this.file,
                    });
                }
            },
        },
    }
</script>