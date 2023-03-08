<template>
    <liste/>
</template>

<script>

import Liste from '~/components/Games/Liste.vue';

export default {
    name: 'GamesPageListe',
    layout: 'default',
    components: {
        Liste,
    },
};
</script>


<style scoped>
.entete {
    background: #2a4a59;
    color: #fff;
    padding: 3rem 0;
}

em {
    font-size: 0.7rem;
}
</style>

