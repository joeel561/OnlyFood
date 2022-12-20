<template>
<div class="recipe-listing-overview overview col-12">
    <alert :classes="alert.type" :text="alert.text" v-if="alert.text"/>
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
                <p> Filter by Tags</p>
                <button class="btn-link" @click="resetFilter"> Reset Filter</button>
            </div>
            <div class="filter-search">
                <input type="text" class="form-control"  v-model="tagKeyword" placeholder="Search Filter" />
                <div class="filter-box d-flex align-items-center" v-for="tag in filteredTags" :key="tag.id">
                    <input type="checkbox" :id="tag.id" class="form-check-input" :value="tag.id" v-model="selectedTags" @click="filterTag(tag.id)" />
                    <label :for="tag.id">{{ tag.name }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center gx-2">
        <div class="no-recipes" v-if="recipes.length == 0">
            <div class="recipe-overview-search col-12 col-md-6 d-flex">
                <input type="text" class="form-control"  v-model="searchKeyword" placeholder="Search for recipe name or username" v-on:keyup.enter="onSearchEnter" />
                <button type="button" class="btn recipe-overview-search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="10" cy="10" r="7" />
                        <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>
                </button>
            </div>
            <div class="recipe-overview-filter-btn d-flex justify-content-end col-md-12">
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
            <h2>Recipe Overview</h2>
            <p>You don't have any recipes yet.</p>
            <a href="#search" class="btn btn-secondary">Search</a>	
            <router-link to="/recipe/create" class="btn btn-primary">Create</router-link>
        </div>
        <div class="col-12 recipe-overview-panel" v-else>
            <div class="review-overview-search-wrapper">
                <div class="recipe-overview-search col-12 col-md-6 d-flex">
                    <input type="text" class="form-control"  v-model="searchKeyword" placeholder="Search for recipe name or username" v-on:keyup.enter="onSearchEnter" />
                    <button type="button" class="btn recipe-overview-search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <circle cx="10" cy="10" r="7" />
                            <line x1="21" y1="21" x2="15" y2="15" />
                        </svg>
                    </button>
                </div>
                <div class="recipe-overview-filter-btn d-flex justify-content-end col-md-12">
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
            </div>
            <div class="recipe-overview-search-results d-flex justify-content-center align-items-center flex-column" v-if="resultsFound">
                <p>Your search results: </p>
                <div class="d-flex justify-content-center">
                    <div class="recipe-tag recipe-overview-tag" v-for="result in resultsFound"> {{ result }}</div>
                </div>
            </div>
            <div class="recipe-overview-container d-flex flex-wrap" ref="recipe">
                <div class="col-6 col-md-4 col-lg-3 p-1 p-md-3"  v-for="recipe in recipes" :key="recipe.id">
                    <router-link :to="{ name: 'recipeDetail', params: {id: recipe.id} }" class="recipe-overview-item-link">
                        <recipe-box v-bind:recipe="recipe" v-bind:tags="recipe.tags"></recipe-box>
                    </router-link>
                </div>
            </div>
            <Trigger @triggerIntersected="loadMore" v-if="setTrigger" />
            <div class="is-loading" v-if="loading">
                <div class="spinner-border text-primary" role="status">
                    <img src="../../../img/peach.png">
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    import RecipeBox from "../layout/RecipeBox";
    import FilterComponent from "../layout/FilterComponent";
    import Trigger from "../component/Trigger";

    export default {
        components: {
            RecipeBox,
            FilterComponent,
            Trigger
        },
        data() {
            return {
                recipes: [],
                tags: [],
                setTrigger: false,
                offset: 0,
                selectedTags: [],
                tagKeyword: '',
                loading: false,
                noRecipes: '',
                ingredients: [],
                ingredientKeyword: '',
                searchKeyword: '',
                resultsFound: '',
                 alert: {
                    type: '',
                    text: '',
                }, 
            }
        },

        async mounted() {
            try {
                let response = await this.$axios.get('/api/recipes/overview');
 
                if (response.data) {
                    this.recipes = response.data;
                    this.offset = response.data.length;
                }
            } catch (e) {
                this.alert.text = e.response.data.detail;
                this.alert.type = "alert-danger";
            }
        },

        created() {
            this.$axios
            .get("/api/recipes/showTags")
            .then((response) => {
                this.tags = response.data;
            })
            .catch((e) => {
                this.alert.text = e.response.data.detail;
                this.alert.type = "alert-danger";
            });
        },

        watch: {
            recipes(oldValue, newValue) {
                this.$nextTick(() => {
                    this.setTrigger = false;
                    if (this.recipe && this.$refs.recipe.offsetHeight > 1000) {
                        this.setTrigger = true;
                    }
                });
            }
        },

        
        computed: {
            filteredTags() {
                return this.tags.filter(
                    tag => tag.name.toLowerCase().includes(this.tagKeyword.toLowerCase())
                );
            }
        },

        methods: {
            loadMore() {
                this.loading = true;
                let response = this.$axios.get('/api/recipes/overview', {
                params: {
                    offset: this.offset
                }
                }).then((response) => {
                    this.loading = false;
                    if (response.data) {
                        this.recipes = [...this.recipes, ...response.data];
                        this.offset += response.data.length;
                    }
                }).catch((e) => {
                    this.loading = false;
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            },
            resetFilter() {
                this.selectedTags = [];
                this.$axios
                .get("/api/recipes/overview")
                .then((response) => {
                    if (response.data) {
                        this.recipes = response.data;
                    }
                })
                .catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            },

            filterTag(id) {
                if (!this.selectedTags.includes(id)) {
                    this.selectedTags.push(id);
                } else {
                    this.selectedTags = this.selectedTags.filter(tag => tag != id);
                }
                this.$axios
                .get("/api/recipes/overview", {
                    params: {
                        tags: this.selectedTags
                    }
                })
                .then((response) => {
                    this.recipes = response.data;
                })
                .catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            },

            onSearchEnter() {
                const searchKeywords = this.searchKeyword.split(/[.\s]+/);

                this.$axios
                .get("/api/recipes/searchResult", {
                    params: {
                        search: searchKeywords
                    }
                })
                .then((response) => {
                    this.recipes = response.data;
                    this.resultsFound = searchKeywords;
                })
                .catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                    this.recipes = [];   
                }); 

            }
        }
    }
</script>