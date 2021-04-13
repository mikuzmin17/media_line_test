import Router from "vue-router";
import Vue from 'vue';
Vue.use(Router);

import Goods from "@/components/Goods";
import Post from "@/components/Post";
import App from "@/components/App";


const routes = [
    {
        path: "/post/:id",
        name: 'Post',
        title: 'Post',
        component: Post,
        props: true
    },
    {
        path: "/goods",
        component: Goods
    },
    {
        path: "/",
        component: App
    },
    {
        path: "*",
        redirect: { name: "App" }
    }
];

export default new Router({
    mode: "history",
    routes
});
