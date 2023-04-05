<template>
<div class="overview dashboard-overview col-12">
    <div class="dashboard-header d-flex flex-column justify-content-center align-items-center">
        <img src="../../../img/peach.png" alt="Peach" class="img-fluid">
        <h1 class="text-center col-8 col-md-6">Welcome to OnlyFood the place where only food matters.</h1>
    </div>
    <div class="dashboard-boxes d-flex flex-wrap justify-content-center">
        <div class="row">
            <dashboardbox title="Recipes" subtitle="All your creative recipes." icon="dashboard-recipes.svg" link="/recipes"></dashboardbox>
            <dashboardbox title="Weekly Plan" subtitle="Plan your week with good food." icon="dashboard-weeklyplan.svg" link="/weeklyPlan"></dashboardbox>
            <dashboardbox title="Shopping List" subtitle="Automatically generated shopping list." icon="dashboard-shoppinglist.svg" link="/shoppinglist"></dashboardbox>
        </div>
    </div>
    <div class="dashboard-meal-plan" v-if="weeklyPlans.length">
        <h2 class="text-center text-white dashboard-meal-plan-title"> Todays Meal Plan</h2>
        <div class="row recipe-overview-container">
            <div class="col-6 col-md-4 col-lg-3 p-md-3" v-for="weeklyPlan in weeklyPlans" :key="weeklyPlan.id">
                <div class="dashboard-meal-plan-title">
                    <h3>{{ weeklyPlan.meal }}</h3>
                </div>
                <router-link :to="{ name: 'recipeDetail', params: { id: weeklyPlan.recipe.id } }"
                    class="dashboard-meal-plan-link recipe-overview-item-link">
                    <recipe-box v-bind:recipe="weeklyPlan.recipe" v-bind:tags="weeklyPlan.recipe.tags"></recipe-box>
                </router-link>
            </div>
            <div class="col-6 col-md-4 col-lg-3 p-1 p-md-3" v-for="computedPlaceholderRecipe in computedPlaceholderRecipes">
                <div class="dashboard-placeholder"></div>
            </div>
        </div>
    </div>
    <div class="dashboard-recipe-recommendations dashboard-meal-plan" v-if="randomRecipes.length">
        <h2 class="text-center text-white dashboard-meal-plan-title"> Only for you</h2>
        <p class="text-center text-white"> Inspire yourself with new recipes from other users.</p>
        <div class="row recipe-overview-container">
            <div class="col-6 col-md-4 col-lg-3 p-md-3" v-for="randomRecipe in randomRecipes" :key="randomRecipe.id">
                <router-link :to="{ name: 'recipeDetail', params: { id: randomRecipe.id } }"
                    class="dashboard-meal-plan-link recipe-overview-item-link">
                    <recipe-box v-bind:recipe="randomRecipe" v-bind:tags="randomRecipe.tags"></recipe-box>
                </router-link>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    import Dashboardbox from '../component/DashboardBox';
    import RecipeBox from "../layout/RecipeBox";

    export default {
        components: {
            Dashboardbox,
            RecipeBox
        },
        data: function() {
            return {
                weeklyPlans: [],
                placeholderRecipes: [ 
                    { id: 1 },
                    { id: 2 },
                    { id: 3 },
                    { id: 4 }
                ],
                randomRecipes: []
            }
        },

        mounted() {
            if (!this.weeklyPlans.length) {
                this.getTodaysMealPlan();
            }

            if (!this.randomRecipes.length) {
                this.getRandomRecipes();
   
            }
        },
        
        computed: {
            computedPlaceholderRecipes() {
                if (this.weeklyPlans.length && this.weeklyPlans.length < 4) {
                    return this.placeholderRecipes.slice(0, 4 - this.weeklyPlans.length);
                }
            }
        },

        methods: {
            getTodaysMealPlan() {
                this.$axios.post('/api/weekly-plan/todaysmealplan', {
                    date: new Date().getDay()
                }).then(response => {
                    this.weeklyPlans = response.data;
                    this.weeklyPlans = this.weeklyPlans.sort((a, b) => {
                        if (a.weekDaySort < b.weekDaySort) {
                            return -1;
                        }
                        if (a.weekDaySort > b.weekDaySort) {
                            return 1;
                        }

                        if (a.mealSort < b.mealSort) {
                            return -1;
                        }

                        if (a.mealSort > b.mealSort) {
                            return 1;
                        }
                        
                        return 0;
                    });
                }).catch(error => {
                    console.log(error);
                });
            },

            getRandomRecipes() {
                this.$axios.get('/api/recipes/random').then(response => {
                    this.randomRecipes = response.data;
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>