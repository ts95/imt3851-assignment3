<script>
    export default {
        name: 'Carousel',
        props: {
            images: {
                required: true,
            }
        },
    }
</script>


<template>
    <div class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li v-for="slide in images" data-slide-to="{{ $index }}"></li>
        </ol>

        <div class="carousel-inner" role="listbox">
            <div v-for="slide in images" class="item" :class="{ 'active' : $index == 0 }">
                <img :src="'/public/item/img/' + slide.filename">
            </div>
        </div>

        <a class="left carousel-control" href="#" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>

        <a class="right carousel-control" href="#" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</template>