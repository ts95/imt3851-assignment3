import 'babel-polyfill';
import 'dom-shims';

import Vue from 'vue';
import Resource from 'vue-resource';
import Router from 'vue-router';
import AsyncData from 'vue-async-data';

import App from '../vue/App.vue';

import HomePage from '../vue/HomePage.vue';

import LoginPage from '../vue/auth/LoginPage.vue';
import RegisterPage from '../vue/auth/RegisterPage.vue';

import NewItemPage from '../vue/item/NewItemPage.vue';
import ItemPage from '../vue/item/ItemPage.vue';

import MessagePage from '../vue/message/MessagePage.vue';

Vue.use(Router);
Vue.use(Resource);
Vue.use(AsyncData);

// Use application/x-www-form-urlencoded instead of
// application/json since PHP doesn't support the latter.
Vue.http.options.emulateJSON = true;

let router = new Router({
    history: true,
    linkActiveClass: 'active',
});

router.map({
    '/': {
        component: HomePage,
    },
    '/auth/login': {
        component: LoginPage,
    },
    '/auth/register': {
        component: RegisterPage,
    },
    '/item/:id': {
        component: ItemPage,
    },
    '/item/new-item': {
        auth: true,
        component: NewItemPage,
    },
    '/messages': {
        auth: true,
        component: MessagePage,
    },
    '/messages/:id': {
        auth: true,
        component: MessagePage,
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
