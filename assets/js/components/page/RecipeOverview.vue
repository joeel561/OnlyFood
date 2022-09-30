<template>
<div class="recipe-listing-overview overview col-12">
    <div class="offcanvas offcanvas-start offcanvas-navigation" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
        <div class="offcanvas-body accordion" id="filterIngredientsOverview">
            <div class="filter-headline d-flex justify-content-between align-items-center">
                    <p> Filter by</p>
                    <button class="btn-link" @click="resetFilter"> Reset Filter</button>
            </div>
            <div class="filter-search">
                <input type="text" class="form-control"  v-model="tagKeyword" placeholder="Search Filter" />
                <div class="filter-box d-flex align-items-center" v-for="tag in filteredTags" :key="tag.value" @click="filterTag">
                    <input type="checkbox" :id="tag.value" class="form-check-input" :value="tag.value" v-model="selectedTags" />
                    <label :for="tag.value">{{ tag.name }}</label>
                </div>
            </div>

            <div class="filter-ingredients accordion-item">
                <div class="filter-ingredients-headline d-flex align-items-center justify-content-between accordion-header collapsed" id="filterIngredientsHeading"  data-bs-toggle="collapse" data-bs-target="#filterIngredientsCollapse" aria-expanded="true" aria-controls="filterIngredientsCollapse">
                    <p> Don't show me</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="accordion-collapse collapse show"  id="filterIngredientsCollapse" aria-labelledby="filterIngredientsHeading" data-bs-parent="#filterIngredientsOverview">
                    <button class="btn-link" @click="resetIngredients"> Clear</button>
                    <input type="text" class="form-control"  v-model="ingredientKeyword" placeholder="Search Ingredients" />
                    <div class="filter-box d-flex align-items-center" v-for="ingredient in filteredIngredients" :key="ingredient.id" @click="filterIngredient">
                        <input type="checkbox" :id="ingredient.name" class="form-check-input" :value="ingredient.name" v-model="selectedIngredients" />
                        <label :for="ingredient.name">{{ ingredient.name }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center gx-2">
        <div class="no-recipes" v-if="recipes.length == 0">
            <h2>Recipe Overview</h2>
            <p>You don't have any recipes yet.</p>
            <a href="#search" class="btn btn-secondary">Search</a>	
            <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
        </div>
        <div class="col-12 recipe-overview-panel" v-else>
            <div class="recipe-overview-filter-btn d-flex justify-content-end col-12">
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvasFilter" role="button" aria-controls="offcanvasFilter">
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
                <div class="col-6 col-md-4 col-lg-3 p-1 p-md-3"  v-for="recipe in filteredRecipes" :key="recipe.id">
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
    import FilterComponent from "../layout/FilterComponent";

    export default {
        components: {
            RecipeBox,
            FilterComponent
        },
        data() {
            return {
                recipes: [],
                tags: [],
                selectedTags: [],
                tagKeyword: '',
                ingredients: [],
                ingredientKeyword: '',
                selectedIngredients: []
            }
        },

        created() {
            this.$axios
            .get("/api/recipes/overview")
            .then((response) => {
                this.recipes = response.data;

                console.log(this.ingredients);

                this.recipes.forEach((recipe) => {
                    recipe.tags.forEach((tag) => {
                        if (!this.tags.some(elm => elm['name'] === tag['name'])) {
                            this.tags.push(tag);
                        }
                    });

                    recipe.ingredients.forEach((ingredient) => {
                        if (!this.ingredients.some(elm => elm['name'] === ingredient['name'])) {
                            this.ingredients.push(ingredient);
                        }
                    });
                });
            })
            .catch((e) => {
                console.log(e);
            });
        },

        
        computed: {
            filteredTags() {
                return this.tags.filter(
                    tag => tag.name.toLowerCase().includes(this.tagKeyword.toLowerCase())
                );
            },

            filteredRecipes() {
                if (this.selectedTags.length > 0) {
                    return this.recipes.filter((recipe) => {
                        return this.selectedTags.every((tag) => {
                            return recipe.tags.some((recipeTag) => {
                                return recipeTag.name == tag;
                            });
                        });
                    });
                }

                if (this.selectedIngredients.length > 0) {
                    return this.recipes.filter((recipe) => {
                        return this.selectedIngredients.every((ingredient) => {
                            return recipe.ingredients.some((recipeIngredient) => {
                                return recipeIngredient.name == ingredient;
                            });
                        });
                    });
                }

                return this.recipes;
            },


            filteredIngredients() {
                return this.ingredients.filter(
                    ingredient => ingredient.name.toLowerCase().includes(this.ingredientKeyword.toLowerCase())
                );
            }
        },

        methods: {
            resetFilter() {
                this.selectedTags = [];
            },

            filterTag() {


            },
            filterIngredient() {

            },
            resetIngredients() {
                this.selectedIngredients = [];
            },
        }
    }
</script>