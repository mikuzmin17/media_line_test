require('./bootstrap');

import Vue from 'vue';
import App from './components/App';
import router from "./router/";

Vue.component("HeaderApp", require("@/components/HeaderApp").default);
Vue.component("FooterApp", require("@/components/FooterApp").default);

const app = new Vue({
    el: '#app',
    router,
    components: {
        App
    },
});


