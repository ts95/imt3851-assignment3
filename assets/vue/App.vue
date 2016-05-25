<script>
    import Navbar from './Navbar.vue';    

    export default {
        name: 'App',
        components: {
            Navbar,
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
    </div>
</template>
