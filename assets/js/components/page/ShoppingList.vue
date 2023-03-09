
<template>
    <div class="recipe-listing-overview overview col-12">
        <alert :classes="alert.type" :text="alert.text" v-if="alert.text"/>
        <h1>Shopping List</h1>
        <p class="text-white col-md-7">The Shopping List will always be overwritten by the weekly plan. Make sure to first plan your week & then add more items.</p>
        <div class="shopping-list-overview--panel panel d-flex flex-wrap">
            <div class="shopping-list col-12 col-md-8">
                <div class="d-flex shopping-list-create">
                    <input type="number" class="form-control item-quantity--input" min="0" placeholder="Quantity" v-model="shoppingQuantityValue"/>
                    <input type="text" class="form-control item-unit--input" placeholder="Unit" v-model="shoppingUnitValue"/>
                    <input type="text" class="form-control item-name--input" placeholder="Name" v-model="shoppingNameValue" v-on:keyup.enter="addShoppingList()"/>
                    <button class="btn btn-primary" @click="addShoppingList">Add</button>
                </div>
                <div  v-if="ingredients.length > 0">
                    <div class="shopping-item" v-for="(item, index) in ingredients" :key="item.id">
                        <div class="shopping-item--icon" @click="showChangeInput(index)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="52" height="52" viewBox="0 0 24 24" stroke-width="1.5" stroke="#1a1a1a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </div>
                        <div class="shopping-item--checkbox d-flex">
                            <input type="checkbox" :id="index">
                            <label class="shopping-item--text" :for="index">
                                <span class="shopping-item--quantity">{{ item.quantity }} {{ item.unit }}</span>
                                <span> {{ item.name }}</span>
                            </label>
                        </div>
                        <div class="shopping-item--change" :class="{ active: index == showChange }">
                            <input type="number" class="form-control item-quantity--input" min="0" :placeholder="item.quantity" v-model="item.quantity" v-on:blur="changeItem (item, index)"/>
                            <input type="text" class="form-control item-unit--input" :placeholder="item.unit" v-model="item.unit" v-on:blur="changeItem (item, index)"/>
                            <input type="text" class="form-control item-name--input" :placeholder="item.name" v-model="item.name" v-on:blur="changeItem (item, index)"/>
                        </div>
                        <div class="shopping-item--update">
                            <div class="shopping-item--icon" @click="removeItem(index)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#1a1a1a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="text-center mt-3 text-white">No items in your shopping list yet.</p>
                </div>
                
                <button  type="button" class="btn btn-primary create-shopping-list-btn" data-bs-toggle="modal" data-bs-target="#clearShoppingListModal" v-if="ingredients.length > 0">Clear shopping list</button>
            </div>
            <div class="shopping-list-bg">
                <img src="../../../img/shopping-list-bg.svg" alt="shopping-list-bg">
            </div>
        </div>
        <div class="modal fade weekly-plan-modal" id="clearShoppingListModal" tabindex="-1" aria-labelledby="clearShoppingListLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-login-content">
                    <button type="button" class="btn-close mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-header">
                        <h3 class="modal-title">Delete shopping list</h3>
                    </div>
                    <div class="modal-body">
                        <p>
                        Do you really want to delete your shopping list?
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary" aria-label="Create" data-bs-dismiss="modal" @click="clearShoppingList()">Yes</button>
                    </div>
                    <div class="bg-orange">
                        <svg id="Ebene_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 132.00003 134.48258"><path class="cls-1" d="M38.67272,41.67267c6.22879-4.7299,12.16921-9.40212,18.28265-13.84361,1.44185-1.09595,3.34509-1.21132,5.07531-1.84582-.92278,1.49973-1.49952,3.34554-2.76835,4.55686-7.72831,6.92181-15.57197,13.72825-23.35796,20.53469-.28837,.23073-1.90324-.51913-2.01859-.98059-.51907-3.98004,1.44185-6.40267,4.78694-8.42153"/><path class="cls-1" d="M110.13077,92.77867c-.28837,1.84582-.2307,3.34554-.80743,4.44149-.28837,.6345-1.61487,.74986-2.53765,.86523-.2307,0-.63441-1.269-.69209-2.07654-.11535-1.09595-.28837-2.13422,0-3.11481,.40372-1.49973,1.0958-2.99945,1.73022-4.44149,.69209,1.15363,1.49953,2.48032,2.30696,4.32613"/><path class="cls-1" d="M42.76757,126.46479c.69209,.74986,1.49952,1.269,1.61487,1.84582,.28837,2.07654,.40372,4.0954,.51907,6.17195-.80743-.23073-2.13394-.23073-2.36463-.74986-.80743-2.42264-3.57579-5.01831,.2307-7.2679"/><path class="cls-1" d="M62.03068,72.99384c-1.09581,.86523-1.90324,1.38436-2.76835,2.07654,.11535-1.96118-.28837-4.21076,.40372-5.71049,.51907-.98059,2.76835-.86523,4.26788-1.269-.11535,1.09595,0,2.13422-.28837,3.23018-.17302,.46146-.86511,.9229-1.61487,1.67277"/><path class="cls-1" d="M32.09789,100.50802c1.3265-1.38436,2.36463-2.59568,3.46044-3.57626,.28837-.34609,1.49952-.34609,1.61487,0,.40372,.51913,.63441,1.61509,.28837,2.07655-1.3265,1.96118-2.76835,3.69163-4.26787,5.53745-.40372-1.32668-.92278-2.48032-1.09581-4.03772"/><path class="cls-1" d="M56.95537,110.77537c-.69209,.34609-1.44185,.51913-1.73022,.23073-1.44185-1.09595-2.76835-2.36495-4.03718-3.57626,1.21115-.23073,2.53765-.86523,3.63346-.51913,1.49952,.40377,2.94137,1.61509,4.38322,2.48032-.57674,.40377-1.3265,.74986-2.24929,1.38436"/><path class="cls-1" d="M107.07405,115.44759c.2307-1.21132,.28837-1.96118,.69209-2.36495,1.03813-.86523,2.24928-1.61509,3.46044-2.36495-.40372,1.96118-.69209,3.80699-1.3265,5.59512,0,.23073-1.7879-.23073-2.82603-.86523"/><path class="cls-1" d="M66.12553,133.50197c-1.03813,.51913-1.84557,.74986-2.653,.98059,.11535-1.73045,.11535-3.34554,.51907-4.96063,.11535-.51913,1.3265-.63451,1.90324-.98059,.40372,.98059,.92278,1.96118,1.0958,2.94177,.17302,.46146-.34605,1.15364-.86511,2.01886"/><path class="cls-1" d="M97.96156,72.24397c-1.44185-.23073-2.82603-.74986-4.03718-1.73045-.2307-.23073,.40372-1.73045,.69209-2.59568,1.0958,1.21132,2.19161,2.48032,3.34509,4.32613"/><path class="cls-1" d="M77.31428,63.24563c-1.0958-.34609-1.90324-.86523-2.76835-1.49973,.40372-.40377,.92278-.98059,1.3265-1.38436,.63441,.86523,1.0958,1.73045,1.44185,2.88409"/><g><path class="cls-1" d="M21.02451,134.07877c-1.84558-4.32611-1.90326-9.63281-2.24933-14.59344-.40369-6.80646-2.13391-10.15198-6.1134-10.67114,1.03815,3.57629,2.01855,7.21027,2.94135,10.90186,.14246,.3205,.02112,.72888-.10614,1.15729-.07892,.26562-.16016,.539-.18219,.80389-.69214-.34607-1.84558-.40375-2.01862-.98059-.21899-.48181-.4527-.96271-.68701-1.4447-1.61682-3.32611-3.25824-6.70288-.23578-10.78381,.04828-.89294-.08521-1.79596-.21454-2.67102-.17975-1.21661-.35156-2.37915-.01617-3.38556,.34216-1.02655,.76715-2.03503,1.19055-3.03961,1.57483-3.73688,3.1272-7.42035,.30902-11.78461-.69214-1.2113-.63446-3.11481-.92279-4.72986-1.21118,1.09595-2.76837,1.96118-3.63348,3.34552-2.76837,3.57623-3.74878,28.84088-1.49951,32.93628,1.73022,3.11481,3.34509,6.34497,5.07532,9.63281,.95203,1.84076,1.78668,3.83398,2.74402,5.71051h5.83527c-.06848-.13611-.15509-.26489-.21655-.40381Z"/><path class="cls-1" d="M9.83572,80.72318c1.21118-.23071,2.53766-.34607,3.57581,0,4.47382,1.78973,8.56329,.60114,12.79456-.62866,.40527-.1178,.81189-.23596,1.22021-.35193,13.20734-3.80695,23.58862-12.11316,31.83606-23.01495,.1969-.25958,.39276-.51874,.58789-.77698,4.83514-6.39764,9.23761-12.22278,18.32916-9.89417,2.36462,.63452,4.78693,1.2113,7.20923,1.09595,6.63251-.23071,12.16919,2.36493,17.30219,6.57574,4.78693,4.0954,12.16919,5.53741,12.3999,14.24738,.11536,.98059,1.21112,1.96118,1.73022,2.99945,2.94135,6.57568,6.80554,12.97839,8.42041,19.95789,1.13831,4.87262,.80273,10.14075,.46912,15.37891-.08893,1.39655-.17773,2.79095-.23846,4.17517-.41571,8.76929-2.75037,16.88763-6.81775,23.99561h5.74506c.35242-.82953,.70728-1.65802,1.07269-2.48035,6.63251-14.93958,8.82416-30.802,3.69116-46.49146-4.26788-12.74768-9.3432-25.03387-20.30127-34.32062-8.13202-6.80646-16.89844-9.51746-26.01093-12.22852-2.53766-.86523-3.74878-1.49976-4.26788-4.21075-1.49951-8.1908-3.34509-16.43933-4.95996-24.74548-.40369-1.84583-.28839-3.86469-1.09583-5.42206-.17505-.34259-.33911-.69629-.50226-1.0481-1.07318-2.3139-2.11005-4.54938-6.01489-3.0473-1.99756,.79156-4.24139,.64709-6.5224,.50024-1.20941-.07788-2.42914-.15643-3.62817-.0965-.92279,.11536-1.73022,1.2113-2.53766,1.84583,.80743,.86523,1.61487,1.96118,2.65295,2.36493,1.61493,.63452,4.15253,.34613,4.95996,1.3844,.53986,.836,1.11389,1.66278,1.68854,2.49048,3.23175,4.65485,6.4837,9.33881,3.79053,15.85229-3.63348,9.17139-7.09393,18.57349-11.47711,27.34113-1.90326,3.98004-5.47906,8.42151-9.22784,9.74823-3.94983,1.49103-7.69812,3.61267-11.45007,5.73639-7.42426,4.20227-14.86249,8.41248-23.90405,7.76111-1.20514-.07532-2.43481,.16907-3.65729,.41205-.64941,.12909-1.29675,.25775-1.93713,.33783,.40375-2.07654,.80743-4.0954,1.32654-6.17194,.11536-.63452,.40369-1.26904,.69208-1.96118,7.09387-16.32391,19.72449-27.34113,33.33557-36.74329,6.51715-4.44147,14.3031-6.46033,21.57007-9.74817,.44183-.21136,1.09491,.07654,1.74396,.36261,1.29913,.57275,2.58173,1.13818,2.12018-2.32379-.56287-3.53131-2.98718-3.0213-4.89636-2.61969-.24261,.05103-.47693,.10034-.698,.13934-4.15253,.63452-8.65112,1.3844-12.3999,3.23016-8.53577,4.21082-16.78314,9.17145-25.03052,13.95898-1.84558,1.09595-3.74884,2.36499-5.07532,4.0954-7.03619,9.2868-12.68829,19.55414-16.14868,31.09045-3.05676,10.03662-.92279,12.86304,8.53571,11.24792Zm58.5968-41.24243c.23065-3.46088,.6344-6.92175,1.03809-10.26733,0-.40375,.28839-.86523,.5191-1.26898,.69208,.34607,1.9032,.74988,2.01855,1.2113,.28839,3.69165,.28839,7.44098,.5191,11.24792-2.4223,1.9035-4.26788,3.11481-4.09485-.92291Z"/></g></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'ShoppingList',
        data() {
            return {
                alert: {
                    type: '',
                    text: ''
                },
                shoppingNameValue: '',
                shoppingQuantityValue: '',
                shoppingUnitValue: '',
                shoppingList: [],
                ingredients: [],
                isActive: false,
                showChange: null
            }
        },
        created() {
            this.$axios.get('/api/shopping/list')
                .then(response => {
                    this.shoppingList = response.data;
                    this.ingredients = this.shoppingList[0].ingredients;
                })
                .catch(error => {
                    console.log(error);
                });

        },
        methods: {
            addShoppingList() {
                if (this.shoppingNameValue !== '' || this.shoppingQuantityValue !== '') {
                    this.ingredients.push({
                        name: this.shoppingNameValue,
                        quantity: this.shoppingQuantityValue,
                        unit: this.shoppingUnitValue
                    });

                    this.updateIngredients();

                    this.shoppingNameValue = '';
                    this.shoppingQuantityValue = '';
                    this.shoppingUnitValue = '';
                }
            },
            changeItem(item, index) {
                if (item.quantity < 0) {
                    item.quantity = 1;
                    this.alert.type = 'alert-danger';
                    this.alert.text = 'Quantity can not be less than 0';
                } else {
                    this.alert.text = '';
                    this.alert.type = '';
                    
                    this.updateIngredients();
                }
            },

            updateIngredients() {
                this.$axios
                .post('/api/shopping-list/upsert/', {
                    ingredients: this.ingredients,
                }).then((response) => {
                    this.alert.type = 'alert-success';
                    this.alert.text = 'Shopping list successfully updated.';
                    this.showChange = null;
                    setTimeout(() => {
                        this.alert.text = '';
                    }, 3000);
                }).catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            },

            showChangeInput(index) {
                this.showChange = this.showChange === index ? null : index;
            },

            removeItem(index) {
                if (this.ingredients.length >= 2) {
                    this.ingredients.splice(index, 1);

                    this.updateIngredients();
                } else {
                    this.clearShoppingList();
                }
            },
            clearShoppingList() {
                this.ingredients = [];

                this.$axios.delete('/api/shopping-list/delete/')
                .then((response) => {
                    this.alert.type = 'alert-success';
                    this.alert.text = 'Shopping list successfully deleted.';
                    setTimeout(() => {
                        this.alert.text = '';
                    }, 3000);
                }).catch((e) => {
                    this.alert.text = e.response.data.detail;
                    this.alert.type = "alert-danger";
                });
            }
        }
    }
</script>