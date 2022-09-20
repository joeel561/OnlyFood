<template>
<div class="recipe-listing-overview overview col-12">
    <div class="row justify-content-center gx-2">
        <div class="no-recipes" v-if="recipes.length == 0">
            <h2>Recipe Overview</h2>
            <p>You don't have any recipes yet.</p>
            <a href="#search" class="btn btn-secondary">Search</a>	
            <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
        </div>
        <div class="col-12 recipe-overview-panel" v-else>
            <div class="recipe-overview-filter-btn d-flex justify-content-end col-12">
                <a href="#" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments-horizontal d-block d-md-none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="14" cy="6" r="2" />
                        <line x1="4" y1="6" x2="12" y2="6" />
                        <line x1="16" y1="6" x2="20" y2="6" />
                        <circle cx="8" cy="12" r="2" />
                        <line x1="4" y1="12" x2="6" y2="12" />
                        <line x1="10" y1="12" x2="20" y2="12" />
                        <circle cx="17" cy="18" r="2" />
                        <line x1="4" y1="18" x2="15" y2="18" />
                        <line x1="19" y1="18" x2="20" y2="18" />
                    </svg>   
                    <span class="d-none d-md-block"> Filter </span>
                </a>	
                <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
            </div>
            <div class="recipe-overview-container d-flex flex-wrap">
                <div class="col-6 col-md-4 col-lg-3 p-1 p-md-3"  v-for="recipe in recipes" :key="recipe.id">
                    <router-link :to="{ name: 'recipeDetail', params: {id: recipe.id} }" class="recipe-overview-item-link">
                        <recipe-box v-bind:recipe="recipe" v-bind:tags="recipe.tags"></recipe-box>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    import RecipeBox from "../layout/RecipeBox";

    export default {
        components: {
            RecipeBox
        },
        data() {
            return {
                recipes: [],
            }
        },

        created() {
            this.$axios
            .get("/api/recipes/overview")
            .then((response) => {
                this.recipes = response.data;
            })
            .catch((e) => {
                console.log(e);
            });
        }
    }
</script>