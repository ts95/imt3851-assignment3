<script>
    export default {
        name: 'Carousel',
        props: {
            cid: {
                required: true,
            },
            images: {
                required: true,
            }
        },
    }
</script>

<template>
    <div :id="cid" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li v-for="slide in images" :data-target="'#' + cid" :data-slide-to="$index"></li>
        </ol>

        <div class="carousel-inner" role="listbox">
            <div v-for="slide in images" class="item" :class="{ active: $index == 0 }">
                <img :src="`/public/item/img/${slide.filename}`">
            </div>
        </div>

        <a class="left carousel-control" :href="'#' + cid" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>

        <a class="right carousel-control" :href="'#' + cid" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</template>

<style lang="sass">
    .carousel {
        img {
            height: 500px !important;
            margin: 0 auto !important;
        }
    }    
</style>
