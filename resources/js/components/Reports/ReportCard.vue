<template>
    <div class="card-container" :style="{background:bgcolor}">
        <div class="icon">
            <i :class="iconname"></i>

        </div>
        <div class="top">

            <h1>{{numbers}}</h1>
        </div>
        <div class="middle"></div>
        <div class="bottom"><span>{{title}}</span></div>
    </div>
</template>
<script>
export default {
    props: ['title', 'numbers', 'iconname', 'bgcolor'],
    setup() {

    },
}
</script>
<style scoped>
.card-container {
    display: grid;
    place-items: center;
    padding: 20px;
    color: white;
    position: relative;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.card-container:hover {
    box-shadow: 0 0 11px rgba(33, 33, 33, 0.8);
    transform: translateY(-10px);
    cursor: pointer;
}

.icon {
    position: absolute;
    top: -10px;
    left: -10px;
    background: rgba(255, 255, 255, 0.3);
    padding: 20px 20px 14px 25px;
    border-radius: 50%;
}
.middle {
    height: 2px;
    width: 70%;
    background: rgba(255, 255, 255, 0.3);
    margin-bottom: 20px;
}
h1 {
    font-size: 50px;
}
span {
    font-size: 22px;
    color: rgba(255, 255, 255, 0.8);
}
</style>