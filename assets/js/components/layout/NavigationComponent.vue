<template>
    <div class="navigation-wrapper">
        <div class="col-md-12 d-flex navigation-container justify-content-md-between align-items-center" :class="{ 'public-navigation': !loggedIn }">
            <div class="col-1 d-md-none mobile-menu" :class="{ 'd-none': !loggedIn }">
                <a class="btn btn-menu" data-bs-toggle="offcanvas" href="#offcanvasMenu" role="button" aria-controls="offcanvasMenu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="4" y1="12" x2="20" y2="12" />
                        <line x1="4" y1="18" x2="20" y2="18" />
                    </svg>
                </a>
            </div>
            <div class="col-md-3 logo">
                <router-link to="/dashboard" v-if="loggedIn"><img src="../../../img/logo.svg" alt="logo" class="img-fluid">	</router-link>
                <img src="../../../img/logo.svg" v-else alt="logo" class="img-fluid">
            </div>
            <div class="col-md-9 navigation d-none d-md-flex justify-content-end" v-if="loggedIn">
                <nav class="nav">
                    <li class="navigation-link"><router-link to="/dashboard/"> Dashboard</router-link></li>
                    <li class="navigation-link"><router-link to="/explore/"> Explore</router-link></li>
                    <li class="navigation-link"><router-link to="/recipes/"> Recipes</router-link></li>
                    <li class="navigation-link"><router-link to="/weeklyplan/"> Weekly Plan</router-link></li>
                    <li class="navigation-link"><router-link to="/shoppinglist/"> Shopping List</router-link></li>
                    <li class="navigation-link dropdown">
                        <a href="#" id="navbarDropdown" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Account </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <router-link to="/account"> Account</router-link>
                            <router-link to="/support"> Support</router-link>
                            <a href="/logout">Logout</a>	
                        </ul>
                    </li>
                </nav>
            </div>
            <div class="offcanvas offcanvas-start offcanvas-navigation" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel" v-if="loggedIn">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
                <div class="offcanvas-body">
                    <ul class="nav nav-mobile">
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/dashboard/"> Dashboard</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/recipes/"> Recipes</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/weeklyplan/"> Weekly Plan</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/shoppinglist/"> Shopping List</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/account/"> Account</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><router-link to="/support/"> Support</router-link></li>
                        <li class="navigation-link" data-bs-dismiss="offcanvas"><a href="/logout">Logout</a></li>
                    </ul>
                </div>
                <div class="offcanvas-bg">
                    <img src="../../../img/carrots.svg" />
                </div>
            </div>
            <div class="col-md-9 d-flex justify-content-end public-navigation-login" v-else>
                <a href="/login" class="btn btn-primary"> Login </a>
                <a href="/register" class="btn btn-secondary d-none d-md-block"> Register </a>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                loggedIn: false,
            }
        },
        created() {
            this.$axios.get('/api/loggedIn')
            .then(response => {
                if (response.data === true) {
                    this.loggedIn = true;
                }
            })
            .catch(error => {
                console.log(error);
            });
        },
    }
</script>