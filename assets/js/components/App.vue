<template>
    <div id='app'>
        <navigation/>
        <div class='container-fluid container-xl'>
            <router-view/>
        </div>
        <Footer/>
    </div>
</template>

<script>
import Navigation from "./layout/NavigationComponent";
import Footer from "./layout/Footer";
    export default {
        components: {
            Navigation,
            Footer
        }       
    }
</script>