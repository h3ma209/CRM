<template>
    <div>
        <Show :heads="['id','name','address','contact_1','note']" :bodys="['1','renwar','suli','0770','Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente, ut dolorum! Vitae molestias eum architecto iste ducimus maxime quisquam quam et incidunt nulla, magnam sequi doloremque culpa magni, id nobis!']" ref="Show">
        </Show>
        
    </div>
</template>

<script>
import Show from './Show.vue';

export default {
    components: { Show },
    methods: {
        Show,
    },
    setup() {

    },
    mounted() {
        this.$emit('showModal')
    }
}


</script>