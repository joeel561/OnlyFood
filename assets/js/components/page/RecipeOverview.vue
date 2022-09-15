<template>
<div class="recipe-listing-overview overview col-12">
    <div class="row justify-content-center">
        <div class="no-recipes" v-if="recipes.length == 0">
            <h2>Recipe Overview</h2>
            <p>You don't have any recipes yet.</p>
            <a href="#search" class="btn btn-secondary">Search</a>	
            <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
        </div>
        <div class="col-12 recipe-overview-panel d-flex justify-content-center flex-wrap" v-else>
            <div class="recipe-overview-filter-btn d-flex justify-content-end col-12">
                <a href="#search" class="btn btn-secondary">Filter</a>	
                <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
            </div>
            <div class="recipe-overview-item col-12 col-lg-3" v-for="recipe in recipes" :key="recipe.id">
                <div class="recipe-overview-img">
                    <img
                        v-if="recipe.imageName"
                        :src="'/images/recipe_pictures/' + recipe.imageName"
                    />
                </div>
                <span> {{ recipe.prepTime }}</span>
                <h3> {{ recipe.name }}</h3>
                <div class="recipe-tags d-flex align-items-center flex-wrap">
                    <div class="recipe-tag recipe-overview-tag" v-for="(tag, index) in recipe.tags">
                        {{ tag.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    export default {
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
                console.log(response.data);
            })
            .catch((e) => {
                console.log(e);
            });
        },
    }
</script>