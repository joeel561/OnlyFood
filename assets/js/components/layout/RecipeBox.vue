<template>
    <div class="recipe-overview-item">
        <div class="recipe-overview-img">
            <img
                v-if="recipe.imageName"
                :src="'/images/recipe_pictures/' + recipe.imageName"
            />
        </div>
        <span class="recipe-overview-preptime"> {{ recipe.prepTime }}</span>
        <h3 class="recipe-overview-name"> {{ recipe.name }}</h3>
        <div class="recipe-tags recipe-overview-tags d-flex align-items-center flex-wrap">
            <div class="recipe-tag recipe-overview-tag" v-for="(tag, index) in recipe.tags.slice(0, 2)">
                {{ tag.name }}
            </div>
            <div class="recipe-tag recipe-tag-more" v-if="recipe.tags.length > 2">
                + {{ recipe.tags.length - 2 }}
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            recipe: Object,
            tags: Array,
        },
    }
</script>