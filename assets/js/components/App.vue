<template>
    <div id='app'>
        <navigation/>
        <div class='container-fluid container-xl'>
            <router-view/>
        </div>
    </div>
</template>

<script>
import Navigation from "./layout/NavigationComponent";
    export default {
        components: {
            Navigation
        }       
    }
</script>