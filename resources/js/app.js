require('./bootstrap');
import Vue from 'vue';
import BootstrapVue from "bootstrap-vue";
// import router from  './components/Router';
import App from './components/App'
Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app',
    components: {
        App
        // router,
    },
});
