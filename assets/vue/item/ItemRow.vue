<script>
    export default {
        name: 'ItemRow',
        props: {
            item: {
                required: true,
            },
        },
    };
</script>

<template>
    <div class="row item-row">
        <div class="col-md-4">
            <img :src="`/public/item/img/${item.filename}`" width="100%">
        </div>
        <div class="col-md-8">
            <h4><a v-link="{ path: `/item/${item.id}` }">{{ item.title }}</a></h4>
            <h6>Given away by <span class="label label-primary">{{ item.giver }}</span></h6>
            <h6><span class="label label-default">{{ item.category_name }}</span></h6>
        </div>
    </div>
</template>

<style lang="sass">
    .item-row {
        margin: 5px 0 10px 0;
        padding: 10px;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
</style>
