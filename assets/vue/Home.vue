<script>
    import Cookies from 'cookies-js';
    import ItemRow from './item/ItemRow.vue';

    export default {
        name: 'Home',
        components: {
            ItemRow,
        },
        data: function() {
            return {
                recentlyAddedItems: [],
                categories: [],
                activeCategoryId: parseInt(Cookies.get('activeCategoryId')) || 1,
            };
        },
        asyncData: async function() {
            let res1 = await this.$http.get(`/a/item/category/${this.activeCategoryId}/recent`);
            let res2 = await this.$http.get('/a/category');

            let recentlyAddedItems = res1.data.items;
            let categories = res2.data.categories;

            return {
                recentlyAddedItems,
                categories,
            };
        },
        props: {
            auth: {
                required: true,
            },
        },
        methods: {
            categoryChange(newId) {
                this.activeCategoryId = newId;
            },
        },
        watch: {
            async activeCategoryId(val, oldVal) {
                Cookies.set('activeCategoryId', val);

                let res = await this.$http.get(`/a/item/category/${val}/recent`);

                this.recentlyAddedItems = res.data.items;
            },
        },
    };
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Recently added items</h3>
                    </div>
                    <div class="panel-body">
                        <span v-if="recentlyAddedItems.length === 0" class="text-muted">
                            This category has no items yet.
                        </span>
                        <item-row v-for="item in recentlyAddedItems" :item="item"></item-row>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Categories</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group" style="margin-bottom: 0">
                            <button v-for="category in categories"
                                :class="activeCategoryId == category.id ? 'list-group-item active' : 'list-group-item'"
                                @click="categoryChange(category.id)">
                                {{ category.name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="sass">

</style>
