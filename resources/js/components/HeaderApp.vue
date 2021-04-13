<template>
    <div class="flex-space ml-auto bt">
        <p>
            <router-link to="/goods"> Товары </router-link>
            <router-link to="/"> Статьи </router-link>
        </p>
    </div>
</template>

<script>
export default {
name: "HeaderApp"
}
</script>

<style scoped>
.flex-space {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
