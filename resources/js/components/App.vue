<template>
    <div>
        <!--        <a class="bt" @click="runParser">Получить свежие статьи</a>-->
        <div
            v-for="(row, index) in posts"
            :key="index"
        >
            <div class="text">
                <h3>{{ row.title }}</h3>
                <p>{{ getLimitSizePost(row.text) }}</p>
            </div>
            <a
                class="bt"
                @click="getPosts(row.id)"
            >
                Подробнее
            </a>
        </div>
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
        }
    },
    created() {
        this.getPosts();
    },
    methods: {
        getPosts(id = '') {
            let self = this;
            axios.get(`api/posts/${id}`)
                .then(response => {
                    if (id) {
                        response.data.text = this.skeepNotify(response.data.text);
                        response.data.img = JSON.parse(response.data.img);
                        this.$router.push({
                            name: 'Post',
                            params: {
                                id: id,
                                post: response.data
                            }
                        })
                    } else self.posts = response.data;
                });
        },

        getLimitSizePost(text) {
            let post = this.skeepNotify(text);

            if (post.length > this.limitLengthPost) {
                post = post.slice(0, this.limitLengthPost) + '... .';
            }
            return post;
        },

        skeepNotify(text) {
            let str = 'Подписка отключает баннерную рекламу на сайтах РБК и обеспечивает его корректную работуВсего 99₽' +
                ' в месяц для 3-х устройствПродлевается автоматически каждый месяц, но вы всегда сможете отписаться';
            return text.replace(str, '');
        },

        runParser() {
            axios.get(`api/parser`).then(r => {
                this.getPosts();
            })
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
</style>
