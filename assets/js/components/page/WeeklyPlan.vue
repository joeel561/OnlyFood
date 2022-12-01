<template>
    <div class="weekly-plan-overview overview col-12">
        <h1>Weekly Plan</h1>
        <p>Add a recipe to your weekly plan. You can select it on the recipe detail page by clicking on the add to weekly plan button.</p>
        <div class="create-recipe-overview--panel panel d-flex flex-wrap" ref="alert">
            <alert :classes="alert.type" :text="alert.text" v-if="alert.text" />
            <div class="weekly-plan-wrapper">
                <div class="weekly-plan-weekday">
                    <h3>Monday</h3>
                    <div class="weekly-plan-boxes">
                        <div class="weekly-plan-box"> 
                            <div class="weekly-plan-box--image">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </div>
                            <div class="weekly-plan-box--text">
                                <p>Add meal</p>
                            </div>
                        </div>
                        <div class="weekly-plan-box"> 
                            <div class="weekly-plan-box--image">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </div>
                            <div class="weekly-plan-box--text">
                                <p>Add meal</p>
                            </div>
                        </div>
                        <div class="weekly-plan-box"> 
                            <div class="weekly-plan-box--image">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </div>
                            <div class="weekly-plan-box--text">
                                <p>Add meal</p>
                            </div>
                        </div>
                        <div class="weekly-plan-box"> 
                            <div class="weekly-plan-box--image">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </div>
                            <div class="weekly-plan-box--text">
                                <p>Add meal</p>
                            </div>
                        </div>
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
                alert: {
                    type: "",
                    text: "",
                },
            }
        }
    }
</script>