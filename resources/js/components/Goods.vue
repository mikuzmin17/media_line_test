<template>
    <div><h2 class="text">Наличие товара</h2>
        <div class="contein">
            <h5 class="text"> Курс валюты </h5>
            <input
                class="course"
                type="range"
                v-model="course"
                min="20"
                max="80"
                step="1">
        </div>

        <div class="contein">
            <table class="table" v-for="(groupId, index) in goods"
                   :key="index">
                <thead class="name-table">
                <tr v-if="names[groupId]">
                    <th>{{ names[groupId].G }}</th>
                    <th> Количество</th>
                    <th> Цена</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index1) in filterGoods(mydata.Value.Goods, groupId)"
                    :key="index1">
                    <td>
                        {{ names[groupId].B[item.T].N }}
                    </td>
                    <td class="contein">
                        <input
                            v-model="item.P"
                            type="text"
                            ref="numberInput"
                            @keyup="validateForm(item.P, index1, 'numberInput')"
                        /> шт.
                    </td>
                    <td
                        class="price-item"
                        :style="{
                        background: color
                        }"
                    >
                        {{ getPrice(item.C) }}
                    </td>
                    <td>
                        <button
                            class="add-cart"
                            @click="addToCart(item)"
                        >В корзину
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <hr class="line"/>
        <h2 class="text">Корзина</h2>
        <table class="table">
            <thead class="name-table">
            <tr>
                <th> Наименование товара и описание</th>
                <th> Количество</th>
                <th> Цена</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(product, index2) in cart"
                :key="index2">
                <td v-if="names[product.G]">
                    {{ names[product.G].B[product.T].N }}
                </td>
                <td class="contein">
                    <input
                        v-model="product.P"
                        type="text"
                        ref="cartInput"
                        @keyup="validateForm(product.P, index2, 'cartInput')"
                    /> шт.
                </td>
                <td class="price-item">
                    {{ getPrice(product.C) }}
                </td>
                <td>
                    <button
                        class="add-cart"
                        @click="removeFromCart(product)"
                    >Удалить
                    </button>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Общая стоимость:</td>
                <td>{{ summaCart }} руб.</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Goods",
    data() {
        return {
            timeout: 15000,
            limitLengthPost: 200,
            course: 70,
            posts: [],
            mydata: [],
            names: [],
            goods: [],
            cart: [],
            color: "",
        }
    },

    watch: {
        "cart": {
            handler: function (card) {
                localStorage.setItem("cart-list", JSON.stringify(this.cart));
            },
            deep: true
        },

        course(newValue, oldValue) {
            if (newValue > oldValue) {
                this.color = 'red';
            } else this.color = 'green';
        },
    },

    computed: {
        summaCart() {
            if (this.cart.length === 1) {
                return Math.abs(this.cart[0].C * this.cart[0].P * this.course).toFixed(2);
            } else if (this.cart.length) {
                return Math.abs((this.cart.reduce(
                    (a, b) => a + (b.C * b.P),
                    0
                    ) * this.course).toFixed(2)
                );
            } else {
                return 0;
            }
        },
    },

    created() {
        this.getMainData();
        this.getTestData();
        this.getTestNames();
    },

    methods: {
        /**
         *
         */
        getGoods() {
            this.goods = this.mydata.Value.Goods.map(item => {
                return item.G;
            });
            this.goods = this.goods.filter((value, index, self) => self.indexOf(value) === index);
        },

        /**
         *
         * @param price
         * @returns {string}
         */
        getPrice(price) {
            return (price * this.course).toFixed(2);
        },

        /**
         *
         * @param value
         * @param index1
         * @param name
         */
        validateForm(value, index1, name) {
            if (!Number.isInteger(+value)) {
                this.$refs[name][index1].value = "";
            } else if ((+value) < 0) {
                this.$refs[name][index1].value = Math.abs(value);
            }

        },

        /**
         *
         * @param itemToRemove
         */
        removeFromCart(itemToRemove) {
            this.cart = this.cart.filter(pr => pr !== itemToRemove);

            this.mydata.Value.Goods.map(item => {
                if (item.G === itemToRemove.G && item.T === itemToRemove.T) {
                    item.P = (+item.P) + (+itemToRemove.P);
                }
                return item;
            });
        },

        /**
         *  P - количество
         *  C - цена
         *  G === groupId -- название из категории names [G]
         *  T === [B] -- название товара из категории names [B]
         * @param item
         */
        addToCart(item) {
            const filterProduct = this.cart.filter(pr => pr.G === item.G && pr.T === item.T && item.P);
            if (filterProduct.length) {
                ++filterProduct[0].P;
                --item.P;
            } else if (item.P) {
                const product = {};
                product.C = item.C;
                product.T = item.T;
                product.G = item.G;
                product.P = 1;
                --item.P;
                this.cart.push(product);
            }
        },

        /**
         *
         * @param allGoods
         * @param groupId
         * @returns {*}
         */
        filterGoods(allGoods, groupId) {
            return allGoods.filter(item => item.G === groupId && this.names[groupId]);
        },

        /**
         *
         */
        getTestData() {
            setInterval(this.getMainData, this.timeout);
        },

        /**
         *
         */
        getMainData() {
            let self = this;
            axios.get(`/data.json`)
                .then(response => {
                    self.mydata = response.data;
                    this.getGoods();
                    const cartData = localStorage.getItem("cart-list");

                    if (cartData) {
                        this.cart = JSON.parse(cartData);
                        this.cart.forEach(pr => {
                            this.mydata.Value.Goods.map(item => {
                                if (item.G === pr.G && item.T === pr.T) {
                                    item.P = item.P - pr.P;
                                }
                                return item;
                            });
                        });
                    }
                });
        },

        /**
         *
         */
        getTestNames() {
            let self = this;
            axios.get(`/names.json`)
                .then(response => {
                    self.names = response.data;
                });
        },
    }
}
</script>

<style scoped>
.text {
    padding: 14px 20px;
    border-radius: 6px;
    background: #F4F7FA;
    vertical-align: baseline;
}

.contein {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 4px;
}

.price-item {
    background: #F4F7FA;
}

.name-table {
    background: #e2f0ec;
}

.line {
    width: 100%;
    margin-top: 1rem;
    margin-bottom: 1rem;
    border-top-width: 3px;
}

.table {
    width: 100%;
}

</style>
