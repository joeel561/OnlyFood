
<template>
    <div class="recipe-listing-overview overview col-12">
        <alert :classes="alert.type" :text="alert.text" v-if="alert.text"/>
        <h1>Shopping List</h1>
        <div class="shopping-list-overview--panel panel d-flex flex-wrap">
            <div class="shopping-list col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Add more food" v-model="shoppingValue" @keyup.enter="addShoppingList"/>
                <div class="shopping-item" v-for="(item, index) in shoppingList" :key="item.id">
                    <div class="shopping-item--checkbox">
                        <input type="checkbox" id="checkItem">
                    </div>
                    <div class="shopping-item--amount">
                        <span>{{ item.amount }}</span>
                        <span>{{ item.unit }}</span>
                    </div>
                    <div class="shopping-item--name">
                        <span v-on:blur="changeItem" contenteditable>{{ item.name }}</span>
                    </div>
                    <div class="shopping-item--delete" @click="removeItem(index)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#1a1a1a" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </div>
                </div>
                <button class="btn btn-primary" @click="clearShoppingList">Clear shopping list</button>
            </div>
            <div class="shopping-list-bg">
                <img src="../../../img/shopping-list-bg.svg" alt="shopping-list-bg">
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
                shoppingValue: '',
                shoppingList: [],
                isActive: false
            }
        },
        created() {
            this.$axios.get('/api/shopping/list')
                .then(response => {
                    this.shoppingList = response.data;
                    console.log(response.data);
                })
                .catch(error => {
                    console.log(error);
                });

        },
        methods: {
            addShoppingList() {
                if (this.shoppingValue !== '') {
                    this.shoppingList.push({
                        id: this.shoppingList.length + 1,
                        name: this.shoppingValue,
                        amount: 1,
                        unit: 'kg'
                    });
                    this.shoppingValue = '';
                }
            },
            changeItem() {
                this.alert.type = 'alert-success';
                this.alert.text = 'Shopping list item changed';
                setTimeout(() => {
                    this.alert.text = '';
                }, 3000);
            },

            removeItem(index) {
                this.shoppingList.splice(index, 1);
            },
            clearShoppingList() {
                this.shoppingList = [];
            }
        }
    }
</script>