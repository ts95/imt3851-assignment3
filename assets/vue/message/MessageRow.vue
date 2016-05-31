<script>
    export default {
        name: 'MessageRow',
        props: {
            message: {
                required: true,
            },
            preview: {
                default: false,
            },
        },
    };
</script>

<template>
    <div class="row message-row">
        <div class="col-md-12">
            <h4><a href="#">{{ message.name }}</a></h4>
            <span v-if="preview">{{ message.message.slice(30) + '...' }}</span>
            <span v-else>{{ message.message }}</span>
        </div>
    </div>
</template>

<style lang="sass">
    .message-row {
        margin: 5px 0 10px 0;
        padding: 10px;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
</style>
