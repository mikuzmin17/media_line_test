<template>
    <div>
        <div
            v-for="(row, index) in posts"
            :key="index"

        >
            <div class="text">
                <h3>{{ row.title }}</h3>
                <p>{{ getLimitSizePost(row.text) }}</p>
            </div>
            <a class="bt cursor-pointer" @click="getPost(row.id)">Подробнее</a>
            <!--        <img>-->
        </div>

        <div class="contein-table">
            <table class="table" v-for="(groupId, index) in goods"
                   :key="index">
                <thead  class="name-table">
                <tr >
                    <th>{{ names[groupId].G }}</th>
                    <th> шт </th>
                    <th> цена </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index1) in filterGoods(mydata.Value.Goods, groupId)"
                    :key="index1">
                    <td>
                        {{ names[groupId].B[item.T].N }}
                    </td>
                    <td>
                        ({{ item.P }})
                    </td>
                    <td class="price-item">
                        {{ item.C }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <p class="text">{{ new Date().getFullYear() }} © Kuzmin Mike </p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "App",
    data() {
        return {
            limitLengthPost: 200,
            posts: [],
            mydata: [],
            names: [],
            goods: [],
        }
    },
    created() {
        // this.getPosts();
        this.getTestData();
        this.getTestNames();

    },
    methods: {
        getGoods() {
            console.log('self.mydata.value.Goods', this.mydata.Value.Goods);
            this.goods = this.mydata.Value.Goods.map(item => {
                console.log(item.G);
                return item.G;
            });
            this.goods = this.goods.filter((value, index, self) => self.indexOf(value) === index);
        },

        filterGoods(allGoods, groupId) {
            return allGoods.filter(item => item.G === groupId);
        },

        getTestData() {
            let self = this;
            axios.get(`/data.json`)
                .then(response => {
                    self.mydata = response.data;
                    this.getGoods();
                });
        },

        getTestNames() {
            let self = this;
            axios.get(`/names.json`)
                .then(response => {
                    self.names = response.data;
                });
        },


        getPosts() {
            let self = this;
            axios.get(`api/posts/`)
                .then(response => {
                    self.posts = response.data;
                });
        },

        getPost(id) {
            let self = this;
            axios.get(`api/posts/${id}`)
                .then(response => {
                    self.posts = response.data;
                    console.log(response.data);
                });
        },

        getLimitSizePost(text) {
            let str = 'Подписка отключает баннерную рекламу на сайтах РБК и обеспечивает его корректную работуВсего 99₽' +
                ' в месяц для 3-х устройствПродлевается автоматически каждый месяц, но вы всегда сможете отписаться';
            text = text.replace(str, '');
            if (text.length > this.limitLengthPost) {
                text = text.slice(0, this.limitLengthPost) + '... .';
            }
            return text;
        }
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

.bt {
    width: 100%;
    height: 51px;
    font-size: 16px;
    line-height: 53px;
    display: block;
    background: #36B25B;
    text-align: center;
}

/*.table {*/
/*    width: 48%;*/
/*}*/
.contein-table {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 10px;
}
.price-item {
    background: #F4F7FA;
}
.name-table {
    background: #e2f0ec;
}
</style>
