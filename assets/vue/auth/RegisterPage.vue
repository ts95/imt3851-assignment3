<script>
    import validator from '../../js/validator';

    export default {
        name: 'RegisterPage',
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
                <h3>Register</h3>
                <hr>
                <form action="/a/auth/register" method="POST" @submit.prevent="submit">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fullName">Full name</label>
                        <input type="text" name="fullName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="repeatPassword">Repeat password</label>
                        <input type="password" name="repeatPassword" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <hr>
                <p>Already have a user? <a v-link="{ path: '/auth/login' }">Log in.</a></p>
            </div>
        </div>
    </div>
</template>
