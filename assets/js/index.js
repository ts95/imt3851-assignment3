import 'babel-polyfill';
import 'dom-shims';

import Vue from 'vue';
import Resource from 'vue-resource';
import Router from 'vue-router';
import AsyncData from 'vue-async-data';

import App from '../vue/App.vue';
import Home from '../vue/Home.vue';

Vue.use(Router);
Vue.use(Resource);
Vue.use(AsyncData);

let router = new Router({
    history: true,
});

router.map({
    '/': {
        component: Home,
    },
});

router.beforeEach((transition) => {
    if (transition.to.auth && !router.app.auth) {
        transition.redirect('/auth/login');
    } else if (transition.to.admin && !router.app.auth.admin) {
        transition.redirect('/');
    } else {
        // Scroll to the top of the window when the user
        // navigates to a new route.
        setTimeout(() => window.scrollTo(0, 0), 100);
        transition.next();
    }
});

router.redirect({
    // TODO: Perhaps change to a 404 route
    '*': '/',
});

router.start(App, '#app');
