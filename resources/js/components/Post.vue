<template>
    <div v-if="post">
        <h1>{{post.title}}</h1>
        <p>{{parseISOString(post.created_at)}}</p>
        <img :src="post.img.src" loading="lazy" :alt="post.img.alt">
        <p>
            {{post.text}}
        </p>
        <a :href="post.href" >Ссылка на оригинальную статью</a>
        Автор:
        <a
        v-if="post.authors.alt"
        :href="post.authors.href[0]" >
       {{post.authors.alt[0]}}
    </a> {{post.img.alt}}
    </div>

</template>

<script>
export default {
    name: "Post",
    props: ['id', 'post'],
    methods: {
        parseISOString(s) {
            console.log(this.post.img.alt);
            let b = s.split(/\D+/);
            return new Date(Date.UTC(b[0], --b[1], b[2], b[3], b[4], b[5], b[6]));
        }
    }
}
</script>

