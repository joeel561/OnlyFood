<template>
    <div class="weekly-plan-overview overview col-12">
        <div class="d-flex justify-content-between align-content-end flex-wrap">
            <div>
                <h1>Weekly Plan</h1>
                <p>Add a recipe to your weekly plan. You can select it on the recipe detail page by clicking on the add to weekly plan button.</p>
            </div>
            <weekly-add-meal v-bind:weeklyPlans="weeklyPlans" @addToWeeklyPlan="getWeeklyPlans()"/>
        </div>
        <div class="weekly-plan-overview--panel panel d-flex flex-wrap" ref="alert">
            <alert :classes="alert.type" :text="alert.text" v-if="alert.text" />
            <div class="no-recipes mt-3" v-if="weeklyPlans.length == 0">
                <p>You don't have any plans yet.</p>
            </div>
            <div class="weekly-plan-wrapper accordion" id="weeklyPlanAccordionParent" v-else>
                <div class="recipe-ingredients accordion-item" 
                v-for="groupedWeeklyPlan in groupedWeeklyPlans">
                    <div class="recipe-ingredients-mobile-headline d-md-none accordion-header collapsed"
                        :id="'headingWeeklyPlan' + groupedWeeklyPlan[0].weekday" 
                        data-bs-toggle="collapse"
                        :data-bs-target="'#collapseWeeklyPlan' + groupedWeeklyPlan[0].weekday"
                        aria-expanded="true" 
                        aria-controls="collapseWeeklyPlan">
                        <h3>{{ groupedWeeklyPlan[0].weekday }}</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0V0z"/>
                            <path class="plus-minus-path" fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 7v10"/>
                            <path fill="none" stroke="rgb(255,255,255)" stroke-dasharray="0 0 0 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 12h10"/>
                        </svg>
                    </div>
                    <div :id="'collapseWeeklyPlan' + groupedWeeklyPlan[0].weekday"  
                        class="recipe-ingredients-mobile-content accordion-collapse collapse"
                        aria-labelledby="headingWeeklyPlan"
                        data-bs-parent="#weeklyPlanAccordionParent">
                        <h3 class="d-none d-md-block">{{ groupedWeeklyPlan[0].weekday }}</h3>
                        <div class="weekly-plan-boxes">
                            <weekly-plan-box  v-for="weeklyPlan in groupedWeeklyPlan"
                            v-bind:weeklyBox="weeklyPlan" :key="weeklyPlan.id" @removeFromWeeklyPlan="getWeeklyPlans()"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" @click="createShoppingList">Create shopping list</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import WeeklyPlanBox from "../layout/WeeklyPlanBox";
    import WeeklyAddMeal from "../layout/WeeklyAddMeal";
    export default {
        components: {
            WeeklyPlanBox,
            WeeklyAddMeal
        },
        data: function() {
            return {
                alert: {
                    type: "",
                    text: "",
                },
                weeklyPlans: [],
                monday: [],
                groupByWeekDay: {},
                tuesday: [],
                wednesday: [],
                groupedWeeklyPlans: [],
                thursday: [],
                friday: [],
                saturday: [],
                sunday: [],
            }
        },

        created() {
            this.getWeeklyPlans();
        },
        
        methods: {
            getWeeklyPlans() {
                this.$axios
                .get('/api/weekly-plan/')
                .then((response) => {
                    this.weeklyPlans = response.data;
                    this.weeklyPlans = this.weeklyPlans.sort((a, b) => {
                        if (a.weekDaySort < b.weekDaySort) {
                            return -1;
                        }
                        if (a.weekDaySort > b.weekDaySort) {
                            return 1;
                        }

                        if (a.mealSort < b.mealSort) {
                            return -1;
                        }

                        if (a.mealSort > b.mealSort) {
                            return 1;
                        }
                        
                        return 0;
                    });

                    this.groupByWeekDay = this.weeklyPlans.reduce((group, weeklyPlan) => {
                        group[weeklyPlan.weekday] = group[weeklyPlan.weekday] || [];
                        group[weeklyPlan.weekday].push(weeklyPlan);
                        return group;
                    }, {});

                    this.groupedWeeklyPlans = Object.keys(this.groupByWeekDay).map(function(key) {
                        return this.groupByWeekDay[key];
                    },this);

                })
                .catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            },
            createShoppingList() {
                if (this.weeklyPlans.length == 0) {
                    this.alert.text = "You don't have any plans yet.";
                    this.alert.type = "alert-danger";
                    return;
                } else {
                    let weeklyplanIngredients = [];
                    this.weeklyPlans.forEach(weeklyPlan => {
                        weeklyplanIngredients.push(weeklyPlan.recipe[0].ingredients);
                    });

                    weeklyplanIngredients = [].concat.apply([], weeklyplanIngredients);

                    this.$axios
                    .post('/api/create/shopping-list/', {
                        ingredients: weeklyplanIngredients
                    }).then((response) => {
                        this.alert.text = "Your shopping list has been created.";
                        this.alert.type = "alert-success";
                    }).catch((e) => {
                        this.alert.text = e.response.data.detail;
                        this.alert.type = "alert-danger";
                    });
                }
            }
        }
    }
</script>