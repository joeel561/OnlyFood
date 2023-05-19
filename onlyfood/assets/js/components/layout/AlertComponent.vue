<template>
    <div class="alert col-12" :class="classes" role="alert">
      {{ text }}
    </div>
</template>
<script>
    export default {
        props: {
            classes: String,
            text: String,
        },
        data: function() {
            return {}
        }
    }

</script>