<script>
    import Carousel from './Carousel.vue';
  
    export default {
        name: 'ItemPage',
        components: {
            Carousel,
        },
        props: {
            auth: {
                required: true,
            },
        },
        data() {
            return {
                item: {},
                images: [],
            };
        },
        async asyncData() {
            let data = {};
            let itemId = this.$route.params.id;

            let res1 = await this.$http.get(`/a/item/${itemId}`);
            let res2 = await this.$http.get(`/a/item/${itemId}/images`);

            if (res1.data.ok)
                data.item = res1.data.item;

            data.images = res2.data.images;

            document.querySelector('title').textContent = data.item.title;

            return data;
        },
        ready() {

        },
        methods: {
            request() {

            },
        },
    };
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <carousel class="carousel" cid="item-carousel" :images="images"></carousel>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Giver</h3>
                    </div>
                    <div class="panel-body">
                        <h3 style="margin: 0">{{ item.giver }}</h3>
                    </div>
                </div>

                <div class="interaction-row">
                    <button class="btn btn-primary" @click="request">Request this item</button>
                    <button class="btn btn-info">Message giver</button>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ item.title }}</h3>
                    </div>
                    <div class="panel-body">
                        <template v-if="item.description">{{ item.description }}</template>
                        <span v-else class="text-muted">No description.</span>
                    </div>
                </div>

                <h5>
                    <span class="label label-default">{{ item.category_name }}</span>
                </h5>
            </div>
        </div>
    </div>
</template>

<style lang="sass">
    .interaction-row {
        margin: 10px 0 10px 0;
    }

    .label {
        margin-top: -5px;
    }
</style>
