<template>
  <div class="create-recipe-overview overview col-12">
    <form id="createRecipe" @submit="checkRecipeForm"  novalidate="true" method="post">
      <h1>Create Recipe</h1>
      <div class="create-recipe-overview--panel panel d-flex flex-wrap" ref="alert">
        <alert :classes="alert.type" :text="alert.text" v-if="alert.text" />
        <div class="img-upload-container recipe-upload-img">
          <div class="upload-img-btn">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon icon-tabler icon-tabler-plus"
              width="36"
              height="36"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="#ffffff"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            <input
              type="file"
              accept="image/*"
              ref="image"
              @change="handleFileUpload()"
            />
          </div>
          <div class="upload-image-preview">
            <img
              v-if="recipe.imageName"
              :src="'/images/recipe_pictures/' + recipe.imageName"
            />
            <img src="../../../img/placeholder.jpg" v-else />
          </div>
        </div>
        <div class="create-recipe-information col-12 col-md-7">
          <input
            type="text"
            class="add-recipe-name form-control"
            placeholder="Recipe Name"
            v-model="recipe.name"
            required
          />
          <input
            type="text"
            class="add-recipe-prep-time form-control mt-3"
            placeholder="Prep Time"
            v-model="recipe.prepTime"
          />
          <multiselect v-model="recipe.difficulty" :options="difficultyOptions"  :allowEmpty="false" class="mt-3" :searchable="false" open-direction="bottom" :close-on-select="false" :show-labels="false" placeholder="Difficulty"></multiselect>
          <multiselect v-model="recipe.tags" :options="tagOptions" :multiple="true" class="mt-3" :close-on-select="false" open-direction="bottom" :clear-on-select="false" :preserve-search="true" :taggable="true" placeholder="Tags for filter" label="name" track-by="name"></multiselect>
        </div>
      </div>

      <div class="panel d-flex create-ingredients-panel flex-wrap mt-5">
        <div class="col-12 mb-3">
          <h2>Ingredients</h2>
        </div>
        <div class="create-ingredients-information col-lg-12 col-xl-10">
          <div class="create-ingredients-element flex-wrap row g-2" v-for="(ingredient, index) in ingredients">
            <div class="col-6 col-md-2">     
              <input
                type="number"
                class="add-recipe-ingredient-quantity form-control"
                placeholder="Quantity"
                v-model="ingredient.quantity"
              />
            </div>
            <div class="col-6 col-md-2">
              <input
                type="text"
                class="add-recipe-ingredient-unit form-control"
                placeholder="Unit"
                v-model="ingredient.unit"
              />
            </div>
            <div class="col-8 col-md-6">
              <input
                type="text"
                class="add-recipe-ingredient-name form-control"
                placeholder="Name"
                v-model="ingredient.name"
              />
            </div>
            <div class="col-2 col-md-auto">
              <a class="btn btn-primary" @click="addIngredient">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <line x1="12" y1="5" x2="12" y2="19" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
              </a>
            </div>
            <div class="col-2 col-md-auto">
              <a class="btn btn-secondary" @click="removeIngredient(index)">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              </a>
            </div>
          </div>
        </div>
        <div class="col-12 mb-3 mt-3">
          <h2>Portion</h2>
        </div>
        <div class="create-portion-element row g-2">
          <div class="col-3 col-md-auto">
            <a class="btn btn-primary" @click="removePortion">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-minus" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg>
            </a>
          </div>
          <div class="col-3">
            <input
              type="text"
              class="add-recipe-ingredient-portion form-control"
              :placeholder="recipe.portion"
              v-model="recipe.portion"
            />
          </div>
          <div class="col-3 col-md-auto">
            <a class="btn btn-primary" @click="addPortion">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg>
            </a>
          </div>
        </div>
        <div class="col-12 mb-3 mt-5">
          <h2>Method</h2>
        </div>
        <div class="create-method-element col-12">
          <vue-editor v-model="recipe.method" />
        </div>
        <div class="col-12 create-ingredients-buttons mt-4 d-flex justify-content-end">
          <div class="col-12 col-md-6 col-lg-4 d-flex">
            <button class="btn btn-secondary" type="reset" @click="cancelRecipe">
              Cancel
            </button>
            <button class="btn btn-primary" type="submit">
              Create Recipe
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import { VueEditor } from "vue2-editor";
export default {
  components: { VueEditor },
  data() {
    return {
      editor: null,
      recipe: {
        id: "",
        name: "",
        prepTime: "",
        imageName: "",
        portion: 1,
        method: '',
        difficulty: '',
        tags: [],
        ingredients: [],
      },
      ingredients: [
          {
            id: '',
            name: '',
            quantity: '',
            unit: '',
          },
      ],
      difficultyOptions: ['easy', 'medium', 'hard'],
      tagOptions: [
          { name: "breakfast", value: "breakfast" },
          { name: "lunch", value: "lunch" },
          { name: "dinner", value: "dinner" },
          { name: "cold food", value: "cold_food" },
          { name: "warm food", value: "warm_food" },
          { name: "no animal products", value: "no_animal_products" },
          { name: "no fish & meat", value: "no_fish_meat" },
          { name: "no seafood", value: "no_seafood" },
          { name: "sweet", value: "sweet" },
          { name: "savoury", value: "savoury" },
          { name: "fast", value: "fast" },
          { name: "cheap", value: "cheap" },
          { name: "high protein", value: "high_protein" }
        ],
      file: "",
      previewImage: "",
      alert: {
        type: "",
        text: "",
      },
    };
  },

  created() {
    this.$axios
      .get("/api/createRecipe")
      .then((response) => {
        this.recipe = response.data;
        if (this.recipe.ingredients.length > 0) {
           this.ingredients = this.recipe.ingredients;
           console.log(this.ingredients);
        }
      })
      .catch((e) => {
        this.alert = e.message;
        this.$refs.alert.scrollIntoView();
      });
  },
  methods: {
    checkRecipeForm(e) {
      e.preventDefault();

      if (this.recipe.name == "") {
        this.alert = {
          type: "alert-danger",
          text: "Recipe name is required",
        };
      } else if (this.recipe.method == "") {
        this.alert = {
          type: "alert-danger",
          text: "Method is required",
        };
      } else if (this.recipe.difficulty == "") {
        this.alert = {
          type: "alert-danger",
          text: "Difficulty is required",
        };
      } else if (this.ingredients.length == 0) {
        this.alert = {
          type: "alert-danger",
          text: "Ingredients are required",
        };
      } else {
        this.createRecipe();
      }

      if (this.alert) {
        this.$refs.alert.scrollIntoView();
      }
    },
    handleFileUpload() {
      const previewInput = this.$refs.image.files[0];

      if (previewInput.size > 2000000) {
        this.alert.text = "Image size is too big";
        this.alert.type = "alert-alert-danger";
        this.$refs.alert.scrollIntoView();
        return;
      }

      const formData = new FormData();
      formData.append("file", previewInput);

      this.$axios
        .post(`/api/recipe/${this.recipe.id}/uploadRecipeImage`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.recipe = response.data;
          this.alert.text = 'Image sucessfully uploaded';
          this.alert.type = "alert-success";
          this.$refs.alert.scrollIntoView();
        })
        .catch((e) => {
          this.alert.text = e.response.data.detail;
          this.alert.type = "alert-danger";
          this.$refs.alert.scrollIntoView();
        });
    },

    addIngredient() {
      this.ingredients.push({
        id: "",
        name: '',
        quantity: '',
        unit: '',
      });
    },

    removeIngredient(index) {
      if (this.ingredients.length > 1) {
        this.ingredients.splice(index, 1);
      }
    },

    addPortion() {
      this.recipe.portion++;
    },

    removePortion() {
      if (this.recipe.portion > 1) {
        this.recipe.portion--;
      }
    },

    createRecipe(e) {
      this.$axios
        .post(`/api/recipe/${this.recipe.id}/updateRecipe`, {
          name: this.recipe.name,
          ingredients: this.ingredients,
          portion: this.recipe.portion,
          prepTime: this.recipe.prepTime,
          method: this.recipe.method,
          difficulty: this.recipe.difficulty,
          tags: this.recipe.tags,
        })
        .then((response) => {
          this.recipe = response.data;
          this.alert.text = 'Recipe updated successfully';
          this.alert.type = "alert-success";
          this.$refs.alert.scrollIntoView();
        })
        .catch((e) => {
          this.alert.text = e.response.data.detail;
          this.alert.type = "alert-danger";
          this.$refs.alert.scrollIntoView();
        });
    },
    cancelRecipe() {
      this.$axios.delete(`/api/recipe/${this.recipe.id}/cancelRecipe`).then(() => {
        this.$router.push({ name: 'createRecipe'});
      });
    },
  },
};
</script>
