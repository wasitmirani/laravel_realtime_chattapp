/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import VueRouter from "vue-router";
// with ES6 import

import io from 'socket.io-client';
window.io = io;

//paste socket server url 
window.socket = io('http://localhost:3000');

Vue.use(VueRouter);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "Login-component",
    require("./components/LoginComponent.vue").default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [,
    {
        path: "/",
        redirect: "/login"
    },
    {
        path: "/login",
        component: require("./components/LoginComponent").default
    },
    {
        path: "/home",
        component: require("./components/HomeComponent.vue").default,
        beforeEnter: (to, from, next) => {
            // ...
            if (localStorage.getItem("token")) {
                next();
            } else {
                window.location.href = "http://localhost/chat_app/#/login";
            }
        }
    }
];

//globaly url veriable
Vue.prototype.$host_url = "http://localhost/chat_app/api/";

const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
    el: "#app",
    router
});
