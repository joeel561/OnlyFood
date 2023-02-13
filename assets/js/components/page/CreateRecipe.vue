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
              accept="image/png, image/jpeg"
              ref="image"
              @change="handleFileUpload()"
            />
          </div>
          <div class="upload-image-preview" :class="errorField.image ? 'input-error' : ''">
            <img
              v-if="previewImage" :src="previewImage" />
            <img src="../../../img/placeholder.jpg" v-else />
          </div>
          <label class="error" v-if="errorField.image">Image is required.</label>
        </div>
        <div class="create-recipe-information col-12 col-md-7">
          <input
            type="text"
            class="add-recipe-name form-control"
            placeholder="Recipe Name"
            v-model="recipe.name"
            required
            :class="errorField.name ? 'input-error' : ''"
            @input="errorField.name = false"
          />
          <label class="error" v-if="errorField.name">Recipe Name is required.</label>
          <input
            type="text"
            class="add-recipe-prep-time form-control mt-3"
            placeholder="Prep Time"
            v-model="recipe.prepTime"
          />
          <multiselect v-model="recipe.difficulty" :options="difficultyOptions"  :allowEmpty="false" class="mt-3" :searchable="false" open-direction="bottom" :close-on-select="false" :show-labels="false" placeholder="Difficulty" :class="errorField.difficulty ? 'input-error' : ''" @input="errorField.difficulty = false"></multiselect>
          <label class="error" v-if="errorField.difficulty">Difficulty is required.</label>
          <multiselect v-model="recipe.tags" :options="tagOptions" :multiple="true" class="mt-3" :close-on-select="false" open-direction="bottom" :clear-on-select="false" :preserve-search="true" :taggable="true" placeholder="Tags for filter" label="name" track-by="name" :class="errorField.tags ? 'input-error' : ''" @input="errorField.tags = false"></multiselect>
          <label class="error" v-if="errorField.tags">At least 1 Tag is required.</label>
        </div>
      </div>

      <div class="panel d-flex create-ingredients-panel flex-wrap mt-5">
        <div class="col-12 mb-3">
          <h2>Ingredients</h2>
        </div>
        <div class="create-ingredients-information col-lg-12 col-xl-10">
          <div class="create-ingredients-element flex-wrap row g-2" v-for="(ingredient, index) in recipe.ingredients">
            <div class="col-6 col-md-2">     
              <input
                type="number"
                class="add-recipe-ingredient-quantity form-control"
                max="1000"
                placeholder="Quantity"
                v-model="ingredient.quantity"
                :class="errorField.ingredientQuantity ? 'input-error' : ''"
                @change="updateQuantity(index)"
                @input="errorField.ingredientQuantity = false"
              />
              <label class="error" v-if="errorField.ingredientQuantity">Quantity required.</label>
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
                :class="errorField.ingredientName ? 'input-error' : ''"
                v-model="ingredient.name"
                @input="errorField.ingredientName = false"
              />
              <label class="error mb-1" v-if="errorField.ingredientName">At least 1 Ingredient Name is required.</label>
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
              type="number"
              class="add-recipe-ingredient-portion form-control"
              max="100"
              :placeholder="recipe.portion"
              v-model="recipe.portion"
              @change="updatePortion"
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
          <vue-editor v-model="recipe.method" :class="errorField.method ? 'input-error' : ''" @input="errorField.method = false" />
          <label class="error" v-if="errorField.method">Method is required.</label>
        </div>
        <div class="col-12 create-ingredients-buttons mt-4 d-flex justify-content-end">
          <div class="col-12 col-md-6 col-lg-4 d-flex">
            <button class="btn btn-secondary" type="reset"  data-bs-dismiss="modal" @click="cancelRecipe">
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
      errorField: {
        image: false,
        name: false,
        method: false,
        difficulty: false,
        ingredientName: false,
        ingredientQuantity: false,
      },
      editor: null,
      recipe: {
        id: "",
        name: '',
        prepTime: "",
        imageName: "",
        portion: 1,
        method: '',
        difficulty: '',
        tags: [],
        ingredients: [
          {
            id: '',
            name: '',
            quantity: '',
            unit: '',
          },
        ],
      },
      difficultyOptions: ['easy', 'medium', 'hard'],
      tagOptions: [
          { name: "breakfast" },
          { name: "lunch" },
          { name: "dinner" },
          { name: "cold food" },
          { name: "warm food" },
          { name: "no animal products" },
          { name: "no fish & meat" },
          { name: "no seafood" },
          { name: "sweet" },
          { name: "savoury" },
          { name: "fast" },
          { name: "cheap" },
          { name: "high protein" }
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
  },

  methods: {
    checkRecipeForm(e) {
      e.preventDefault();

      this.alert = {
        type: "",
        text: "",
      };

      this.errorField = {
        image: false,
        name: false,
        method: false,
        difficulty: false,
        ingredientName: false,
        ingredientQuantity: false,
      };

      if (this.recipe.name == "") {
        this.errorField.name = true;
      }

      if (this.file == "") {
        this.errorField.image = true;
      }

      if (this.recipe.method == "") {
        this.errorField.method = true;
      } 

      if (this.recipe.difficulty == "") {
        this.errorField.difficulty = true;
      } 

      if (this.recipe.tags.length  == 0) {
        this.errorField.tags = true;
      }

      if (this.recipe.ingredients.length < 1) {
      } else {
        this.recipe.ingredients.forEach((ingredient) => {
          if (ingredient.name == "") {
            this.errorField.ingredientName = true;
          } 
          
          if (ingredient.quantity == "") {
            this.errorField.ingredientQuantity = true;
          }
        });
      }

      if (this.errorField.name || this.errorField.image || this.errorField.method || this.errorField.difficulty || this.errorField.ingredientName || this.errorField.ingredientQuantity) {
        this.$refs.alert.scrollIntoView();
      } else {
        console.log(this.recipe);
        this.createRecipe();
      }
    },
    handleFileUpload() {
      this.file = this.$refs.image.files[0];
      const previewInput = this.$refs.image.files[0];
      const reader = new FileReader();

      this.errorField.image = false;

      reader.onload = (e) => {
        this.previewImage = e.target.result;
      };

      if (previewInput) {
        reader.readAsDataURL(previewInput);
      }

      if (previewInput.size > 2000000) {
        this.alert.text = "Image size is too big";
        this.alert.type = "alert-alert-danger";
        this.$refs.alert.scrollIntoView();
        return;
      }
    },
    createRecipe(e) {
      const formData = new FormData();
      
      if (this.file) {
        formData.append("file", this.file);
      }
      formData.append("recipe", JSON.stringify(this.recipe));

      this.$axios
        .post('/api/createRecipe', formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.recipe = response.data;
          this.alert.text = 'Recipe successfully created. Before it can be seen by other users it needs to be approved by an admin.';
          this.alert.type = "alert-success";
          this.$refs.alert.scrollIntoView();
          setTimeout(() => {
            this.$router.push({ name: 'overview'});
          }, 2000);
        })
        .catch((e) => {
          this.alert.text = e.response.data.detail;
          this.alert.type = "alert-danger";
          this.$refs.alert.scrollIntoView();
        });
    },

    addIngredient() {
      this.recipe.ingredients.push({
        id: "",
        name: '',
        quantity: '',
        unit: '',
      });
    },

    removeIngredient(index) {
      if (this.recipe.ingredients.length > 1) {
        this.recipe.ingredients.splice(index, 1);
      }
    },

    addPortion() {
      if (this.recipe.portion < 100) {
        this.recipe.portion++;
      }
    },

    updatePortion() {
        if (this.recipe.portion < 1) {
            this.recipe.portion = 1;
        } else if (this.recipe.portion > 100) {
            this.recipe.portion = 100;
        }
    },

    updateQuantity(index) {
        if (this.recipe.ingredients[index].quantity < 0) {
            this.recipe.ingredients[index].quantity = 0;
        } else if (this.recipe.ingredients[index].quantity > 1000) {
            this.recipe.ingredients[index].quantity = 1000;
        }
    },

    removePortion() {
      if (this.recipe.portion > 1) {
        this.recipe.portion--;
      }
    },
    cancelRecipe() {
      this.$router.push({ name: 'overview'});
    },
  },
};
</script>
