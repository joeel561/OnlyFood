import Vue from "vue";
import VueRouter from "vue-router";
import Account from "../components/page/AccountOverview";
import Faq from "../components/page/FaqOverview";
import Recipe from "../components/page/RecipeOverview";
import RecipeDetail from "../components/page/RecipeDetail";
import CreateRecipe from "../components/page/CreateRecipe";
import EditRecipe from "../components/page/EditRecipe";

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
    },
    {   
        path: "/recipe/create/",
        name: "createRecipe",
        component: CreateRecipe 
    },
    {   
        path: "/recipe/edit/:id",
        name: "editRecipe",
        component: EditRecipe 
    },
    {   
        path: "/recipes/",
        name: "recipes",
        component: Recipe 
    },
    {   
        path: "/recipe/:id",
        name: "recipeDetail",
        component: RecipeDetail 
    },    
]

const router = new VueRouter({
    mode: 'history',
    routes 
})

export default router