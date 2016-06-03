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
                giver: {},
            };
        },
        async asyncData() {
            let data = {};
            let itemId = this.$route.params.id;

            let res1 = await this.$http.get(`/a/item/${itemId}`);
            let res2 = await this.$http.get(`/a/item/${itemId}/images`);

            if (res1.data.ok) {
                data.item = res1.data.item;

                let res3 = await this.$http.get(`/a/auth/${data.item.user_id}`);
                
                if (res3.data.ok)
                    data.giver = res3.data.user;
            }

            data.images = res2.data.images;

            return data;
        },
        ready() {

        },
        methods: {
            async request() {
                // Not implemented
            },
            async message() {
                let res = await this.$http.post('/a/message/exchange', {
                    ids: [this.giver.id],
                });

                let exchange = res.data;

                if (exchange.ok) {
                    this.$route.router.go(`/messages/${exchange.id}`);
                }
            },
        },
    };
</script>

<template>
    <div class="container">
        <div class="row" v-if="!$loadingAsyncData">
            <div class="col-md-6">
                <carousel class="carousel" cid="item-carousel" :images="images"></carousel>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Giver</h3>
                    </div>
                    <div class="panel-body">
                        <h3 style="margin: 0">{{ giver.full_name }}</h3>
                    </div>
                </div>

                <div class="interaction-row" v-if="auth.id !== giver.id">
                    <!-- <button class="btn btn-primary" @click="request">Request this item</button> -->
                    <button class="btn btn-info" @click="message">Message giver</button>
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
