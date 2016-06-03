<script>
    export default {
        name: 'PageFooter',
    };
</script>

<template>
    <footer id="page-footer">
        <div class="copyright">Giveaway &copy; 2016</div>
    </footer>
</template>

<style lang="sass">
    #page-footer {
        width: 100%;
        height: 100px;
        padding: 10px;
        margin: 10px 0 0 0;

        .copyright {
            color: #333;
            font-size: 1.2em;
            text-align: center;
        }
    }
</style>
