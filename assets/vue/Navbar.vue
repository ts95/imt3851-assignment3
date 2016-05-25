<script>
    export default {
        name: 'Navbar',
        props: {
            auth: {
                required: true,
            },
        },
        methods: {
            logOut: async function() {
                await this.$http.get('/a/auth/logout');
                this.$root.$data.auth = null;
                this.$route.router.go('/');
            },
        },
    };
</script>

<template>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" v-link="{ path: '/' }">Market</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a v-link="{ path: '/' }">Home</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <template v-if="auth">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ auth.full_name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="logout-button" @click.prevent="logOut" href="#">Log out</a></li>
                                <!-- <li class="dropdown-header"><b>Admin menu</b></li> -->
                            </ul>
                        </li>     
                    </template>
                    <template v-else>
                        <li><a v-link="{ path: '/auth/login'} ">Log in</a></li>
                        <li><a v-link="{ path: '/auth/register'} ">Register</a></li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>
