<template>
    <div class="col-12 col-md-4 dashboard-box-wrapper">
        <a :href="link" class="dashboard-box">
            <div class="dashboard-box-content d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <h2>{{ title }}</h2>
                    <p>{{ subtitle }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-right" viewBox="0 0 24 24" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <line x1="13" y1="18" x2="19" y2="12" />
                    <line x1="13" y1="6" x2="19" y2="12" />
                </svg>
            </div>
            <div class="dashboard-box-icon">
                <img :src="require(`/assets/img/${icon}`)" alt="icon" class="img-fluid">
            </div>
        </a>
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
        },
        subtitle: {
            type: String,
        },
        icon: {
            type: String,
        },
        link: {
            type: String
        }
    },
}
</script>