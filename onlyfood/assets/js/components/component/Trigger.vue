<template>
    <span ref="trigger" class="trigger-span"/>
</template>

<script>
    export default {
        props: {
            options: {
                type: Object,
                default() {
                    return {
                        root: null,
                        rootMargin: '0px 0px 300px 0px',
                        threshold: "0",
                    }
                }
            }
        },

        data() {
            return {
                observer: null
            }
        },

        mounted() {
            this.observer = new IntersectionObserver( entries => {
                this.handleIntersect(entries[0]);
            }, this.options);

            this.observer.observe(this.$refs.trigger);
        },

        beforeDestroy() {
            this.observer.disconnect();
        },

        destroyed() {
            this.observer.disconnect();
        },

        methods: {
            handleIntersect(entry) {
                if (entry.isIntersecting) {
                    this.$emit('triggerIntersected');
                }
            }
        }
    }   
</script>