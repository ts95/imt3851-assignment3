<script>
    import Navbar from './Navbar.vue';
    import PageFooter from './PageFooter.vue';

    export default {
        name: 'App',
        components: {
            Navbar,
            PageFooter,
        },
        data() {
            return {
                auth: $global.auth,
            };
        },
    };
</script>

<template>
    <div>
        <navbar :auth="auth"></navbar>
        <router-view :auth="auth"></router-view>
        <page-footer></page-footer>
    </div>
</template>
