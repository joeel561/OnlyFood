import Vue from "vue";
import VueRouter from "vue-router";
import Account from "../components/page/AccountOverview";
import Faq from "../components/page/FaqOverview";

Vue.use(VueRouter);

const routes = [
    {   path: "/",
        name: "default",
        component: Account 
    },
    {   path: "/account",
        name: "account",
        component: Account
    },
    {   path: "/support",
        name: "support",
        component: Faq 
    }
]

const router = new VueRouter({
    mode: 'history',
    routes 
})

export default router