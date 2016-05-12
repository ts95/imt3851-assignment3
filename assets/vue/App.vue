<script>
    export default {
        name: 'App',
        data() {
            return {
                auth: $global.auth,
            };
        },
    };
</script>

<template>
    <div>
        <router-view :auth="auth"></router-view>
    </div>
</template>
