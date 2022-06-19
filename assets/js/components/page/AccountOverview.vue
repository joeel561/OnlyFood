<template>
  <div class="account-overview col-12">
    <h1>Account Overview</h1>
    <div class="account-overview--panel d-flex flex-wrap">
      <alert classes="alert-danger" :text="alert" v-if="alert"/>
        <div class="account-img-upload col-12 col-md-3">
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
          <img :src="previewImage" v-if="previewImage" />
          <img :src="require('../../../../public/images/profile_pictures/' + profilePicture)" v-else-if="profilePicture" />
          <img src="../../../img/placeholder.jpg" v-else />
        </div>
      </div>
      <div class="account-settings col-12 col-md-9 mt-4 mt-md-0">
        <div class="display-name-wrapper">
          <div class="display-name" :class="{ active: isActive }">
            <div class="display-name-title">Display Name</div>
              <div class="change-username-value">
                <input
                  type="text"
                  class="change-username form-control"
                  v-model="username"
                  :placeholder="this.account.username"
                />
                <button class="btn btn-primary" @click="changeUserInfo()">
                  Save
                </button>
              </div>
            <div class="display-name-value justify-content-between">	
              <div class="display-name-label">{{ this.account.username }}</div>
              <button class="btn btn-secondary" @click="showUsernameInput()">
                Edit
              </button>
            </div>
          </div>
        </div>
        <div class="show-recipes-public d-flex align-content-center">
          <input type="checkbox" v-model="privatMode" class="form-check-input" @change="changeUserInfo()" />
          <div class="show-recipes-title text-small text-white">Show Recipes Publicly</div>
        </div>
      </div>
    </div>
    <div class="account-overview--panel delete-panel d-flex flex-wrap">
      <div class="display-name">
        <div class="display-name-title">Delete Account</div>
        <p class="text-white info-text-delete--panel"> When you delete your account all your recipes & information will be deleted.</p>
      </div>
      <div class="col-12 d-flex justify-content-start flex-column flex-md-row delete-panel--btns">
        <a class="btn btn-secondary" href="/logout">Logout</a>	
        <button class="btn btn-danger" @click="openDeleteModal()" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Account</button>	
        <a class="btn btn-secondary btn-align--right" href="/support">Help/Support</a>	
      </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="deleteModalLabel">Are you sure you want to delete your account?</h3>
          </div>
          <div class="modal-body">
            When you delete your account all your recipes & information will be deleted.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" @click="deleteAccount()">Yes, delete it!</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      account: "",
      errors: [],
      username: "",
      previewImage: "",
      profilePicture: '',
      privatMode:'',
      isActive: false,
      alert: "",
      file: "",
    };
  },

  created() {
    axios
      .get("account/api/details")
      .then((response) => {
        this.account = response.data;
        this.profilePicture = this.account.profilePictureName;
        this.privatMode = this.account.privatMode;
        console.log(this.account);
      })
      .catch((e) => {
        this.errors.push(e);
        console.log(this.errors);
      });
  },

  methods: {
    changeUserInfo() {
      this.isActive = false;
      axios.patch(`account/api/${this.account.id}/changeUserInfo`, {
        name: this.username,
        email: this.account.email,
        privatMode: this.privatMode,
        lightMode: this.account.lightMode
      }).catch((e) => {
        this.errors.push(e);
        console.log(this.errors);
      });

      console.log(this.privatMode);

    //   if (this.errors[0].message) {
    //    console.log(this.errors);
    //    this.alert = "Username is already taken";
    //  }
    },
    uploadImage() {
      const previewInput = this.$refs.image.files[0];
      const reader = new FileReader();

      if (previewInput.size > 2000000) {
        this.alert = "Image size is too big";
        return;
      }

      reader.onload = (e) => {
        this.previewImage = e.target.result;
      };

      if (previewInput) {
        reader.readAsDataURL(previewInput);
      }

      const formData = new FormData();

      formData.append("file", this.file);

      axios.post(
        `account/api/${this.account.id}/uploadProfilePicture`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
    },

    openDeleteModal() {

    },
    

    deleteAccount() {
      axios.delete(`account/api/${this.account.id}/deleteAccount`).then(() => {
        window.location.href = "/login";
      });
    },

    handleFileUpload() {
      this.file = this.$refs.image.files[0];
      this.uploadImage();
    },

    showUsernameInput() {
      this.isActive = true;
    },
  },
};
</script>
