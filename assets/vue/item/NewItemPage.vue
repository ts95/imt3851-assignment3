<script>
    import validator from '../../js/validator';

    export default {
        name: 'NewItemPage',
        props: {
            auth: {
                required: true,
            },
        },
        data() {
            return {
                categories: [],
            };
        },
        async asyncData() {
            let res1 = await this.$http.get('/a/category');

            let categories = res1.data.categories;

            return {
                categories: categories,
            };
        },
        methods: {
            async submit() {
                try {
                    let result = await validator(this);

                    if (result.ok) {
                        let itemId = result.itemId;
                        this.$route.router.go(`/item/${itemId}`);
                    }
                } catch (err) {
                    console.log(err);
                }
            },
        },
    };
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <h3>New item</h3>
                <hr>
                <form action="/a/item/new" method="POST" enctype="multipart/form-data" @submit.prevent="submit">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" name="category">
                            <option v-for="category in categories" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="images">Images</label>
                        <input type="file" accept=".jpg,.png" name="images" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">New item</button>
                </form>
            </div>
        </div>
    </div>
</template>
