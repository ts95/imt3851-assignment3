<script>
    export default {
        name: 'CubeGrid',
        data() {
            return {
                visible: false,
            };
        },
        ready() {
            setTimeout(() => {
                this.visible = true;
            }, 1000);
        },
    };
</script>

<template>
    <div class="cube-grid" v-if="visible">
        <div class="cube cube1"></div>
        <div class="cube cube2"></div>
        <div class="cube cube3"></div>
        <div class="cube cube4"></div>
        <div class="cube cube5"></div>
        <div class="cube cube6"></div>
        <div class="cube cube7"></div>
        <div class="cube cube8"></div>
        <div class="cube cube9"></div>
    </div>
</template>

<style lang="sass">
    @keyframes cube-grid-scale-delay {
        0%, 70%, 100% {
            transform: scale3D(1.0, 1.0, 1.0);
        }
        35% {
            transform: scale3D(0.0, 0.0, 1.0);
        }
    }

    @keyframes fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .cube-grid {
        $delayRange: 0.4s;
        $spinner-margin: 40px auto !default;
        $size: 40px !default;
        $spinner-color: #2196f3 !default;

        width: $size;
        height: $size;
        margin: $spinner-margin;
        animation: fade-in 1s ease-in;

        .cube {
            width: 33.33%;
            height: 33.33%;
            background-color: $spinner-color;
            float: left;
            animation: cube-grid-scale-delay 1.2s infinite ease-in-out;
        }

        /**
         * Spinner positions
         * --------------------
         * 1 2 3
         * 4 5 6
         * 7 8 9
         */

        .cube1 { animation-delay: $delayRange * 0.50 }
        .cube2 { animation-delay: $delayRange * 0.75 }
        .cube3 { animation-delay: $delayRange }
        .cube4 { animation-delay: $delayRange * 0.25 }
        .cube5 { animation-delay: $delayRange * 0.50 }
        .cube6 { animation-delay: $delayRange * 0.75 }
        .cube7 { animation-delay: 0.0s }
        .cube8 { animation-delay: $delayRange * 0.25 }
        .cube9 { animation-delay: $delayRange * 0.50 }
    }
</style>
