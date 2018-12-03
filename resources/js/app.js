require('./bootstrap');

window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue';
window.Vue.use(BootstrapVue);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const components = require.context('./', true, /\.vue$/i);

components.keys().map(key => {
    return Vue.component(_.last(key.split('/')).split('.')[0], components(key))
});

const app = new Vue({
    el: '#app',
    methods: {
        logout: function (event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    }
});
