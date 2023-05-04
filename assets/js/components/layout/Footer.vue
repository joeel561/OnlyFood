<template>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-content">
                        <p>© 2023 All rights reserved. Copyright Joeel56 
                            <router-link to="/dashboard" v-if="loggedIn"><img src="../../../img/peach.png" alt="logo" class="img-fluid"></router-link>
                            <img v-else src="../../../img/peach.png" alt="logo" class="img-fluid">
                        </p> 
                        <nav class="nav">
                            <li class="navigation-link" v-if="loggedIn"><router-link to="/account"> Account</router-link></li>
                            <li class="navigation-link" v-if="!loggedIn"><a href="/login"> Login </a></li>
                            <li class="navigation-link" v-if="!loggedIn"><a href="/register"> Register </a></li>
                            <li class="navigation-link"><router-link to="/imprint/"> Imprint</router-link></li>
                            <li class="navigation-link"><router-link to="/privacy/"> Privacy</router-link></li>
                            <li class="navigation-link"><router-link to="/termsofusage/"> Terms of Usage</router-link></li>
                            <li class="navigation-link"><router-link to="/support"> Support</router-link></li>
                            <li class="navigation-link" v-if="loggedIn"><a href="/logout">Logout</a></li>
                        </nav>
                    </div>
                </div>
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