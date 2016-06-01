<script>
    import validator from '../../js/validator';

    export default {
        name: 'LoginPage',
        props: {
            auth: {
                required: true,
            },
        },
        methods: {
            submit: async function() {
                try {
                    let result = await validator(this);

                    if (result.ok)
                        this.$route.router.go('/');
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
                <h3>Login</h3>
                <hr>
                <form action="/a/auth/login" method="POST" @submit.prevent="submit">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="rememberMe" checked> Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Log in</button>
                </form>
                <hr>
                <p>Don't have a user? <a v-link="{ path: '/auth/register' }">Register a user.</a></p>
            </div>
        </div>
    </div>
</template>
